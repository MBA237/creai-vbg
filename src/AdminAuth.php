<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Admin.php';

class AdminAuth
{
    private mysqli $conn;

    /** État du compte relu en base, mémorisé pour la durée de la requête. */
    private static ?array $live = null;

    /** Nombre max de tentatives avant blocage */
    private const MAX_ATTEMPTS = 5;

    /** Durée du blocage en secondes (15 minutes) */
    private const LOCK_DURATION = 900;

    /** Durée de la session admin (2 heures) */
    private const SESSION_LIFETIME = 7200;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /**
     * Tente de connecter un admin.
     * @return array ['success' => bool, 'error' => ?string, 'admin' => ?array]
     */
    public function login(string $email, string $password): array
    {
        $email = trim(strtolower($email));

        if ($email === '' || $password === '') {
            return ['success' => false, 'error' => 'Email et mot de passe requis.', 'admin' => null];
        }

        // Récupère l'admin
        $stmt = $this->conn->prepare(
            "SELECT id, email, password_hash, nom, role, actif, tentatives_echecs, bloque_jusqua
             FROM admins WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Email inconnu → message générique (ne pas révéler)
        if (!$admin) {
            // Petit délai pour ralentir les attaques
            usleep(300000); // 0.3s
            return ['success' => false, 'error' => 'Identifiants incorrects.', 'admin' => null];
        }

        // Compte actif ?
        if ((int) $admin['actif'] !== 1) {
            return ['success' => false, 'error' => 'Ce compte est désactivé.', 'admin' => null];
        }

        // Compte bloqué ?
        if ($admin['bloque_jusqua'] && strtotime($admin['bloque_jusqua']) > time()) {
            $restant = (int) ceil((strtotime($admin['bloque_jusqua']) - time()) / 60);
            return [
                'success' => false,
                'error'   => "Trop de tentatives. Réessayez dans $restant minute(s).",
                'admin'   => null,
            ];
        }

        // Vérifie le mot de passe
        if (!password_verify($password, $admin['password_hash'])) {
            $this->incrementAttempts((int) $admin['id'], (int) $admin['tentatives_echecs']);
            return ['success' => false, 'error' => 'Identifiants incorrects.', 'admin' => null];
        }

        // Vérifie si le hash doit être rehashé (nouvelles versions de PHP)
        if (password_needs_rehash($admin['password_hash'], Admin::hashAlgo())) {
            $newHash = password_hash($password, Admin::hashAlgo());
            $up = $this->conn->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
            $up->bind_param('si', $newHash, $admin['id']);
            $up->execute();
            $up->close();
        }

        // Réinitialise les tentatives + met à jour la dernière connexion
        $reset = $this->conn->prepare(
            "UPDATE admins SET tentatives_echecs = 0, bloque_jusqua = NULL, derniere_connexion = NOW() WHERE id = ?"
        );
        $reset->bind_param('i', $admin['id']);
        $reset->execute();
        $reset->close();

        // Régénère l'ID de session (protection contre la fixation de session)
        session_regenerate_id(true);

        // Stocke les infos en session
        $_SESSION['admin_id']         = (int) $admin['id'];
        $_SESSION['admin_email']      = $admin['email'];
        $_SESSION['admin_nom']        = $admin['nom'];
        $_SESSION['admin_role']       = $admin['role'];
        $_SESSION['admin_last_activity'] = time();

        return ['success' => true, 'error' => null, 'admin' => $admin];
    }

    /**
     * Incrémente les tentatives et bloque si dépassement.
     */
    private function incrementAttempts(int $id, int $current): void
    {
        $newCount = $current + 1;

        if ($newCount >= self::MAX_ATTEMPTS) {
            $bloque = date('Y-m-d H:i:s', time() + self::LOCK_DURATION);
            $stmt = $this->conn->prepare(
                "UPDATE admins SET tentatives_echecs = ?, bloque_jusqua = ? WHERE id = ?"
            );
            $stmt->bind_param('isi', $newCount, $bloque, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE admins SET tentatives_echecs = ? WHERE id = ?"
            );
            $stmt->bind_param('ii', $newCount, $id);
        }

        $stmt->execute();
        $stmt->close();
    }

    /**
     * Vérifie si un admin est connecté (et si la session n'a pas expiré).
     */
    public static function check(): bool
    {
        if (empty($_SESSION['admin_id'])) {
            return false;
        }

        // Expiration par inactivité
        if (!empty($_SESSION['admin_last_activity'])) {
            if (time() - $_SESSION['admin_last_activity'] > self::SESSION_LIFETIME) {
                self::logout();
                return false;
            }
        }

        // Le compte doit exister et être actif : une désactivation ou une suppression
        // prend effet tout de suite, sans attendre la fin de la session.
        $live = self::liveState((int) $_SESSION['admin_id']);
        if ($live === null || (int) $live['actif'] !== 1) {
            self::logout();
            return false;
        }

        // Le nom et le rôle viennent de la base, pas d'une session parfois ancienne
        $_SESSION['admin_nom']         = $live['nom'];
        $_SESSION['admin_role']        = $live['role'];
        $_SESSION['admin_must_change'] = (int) $live['doit_changer_mdp'];

        // Rafraîchit l'activité
        $_SESSION['admin_last_activity'] = time();
        return true;
    }

    /** Situation actuelle du compte connecté (une seule requête par page). */
    private static function liveState(int $id): ?array
    {
        if (self::$live !== null && (int) self::$live['id'] === $id) {
            return self::$live;
        }

        $conn = (new Database())->connect();
        $stmt = $conn->prepare('SELECT id, nom, role, actif, doit_changer_mdp FROM admins WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        self::$live = $row ?: null;
        return self::$live;
    }

    /** Vrai si la personne connectée doit d'abord choisir un nouveau mot de passe. */
    public static function mustChangePassword(): bool
    {
        return !empty($_SESSION['admin_id']) && !empty($_SESSION['admin_must_change']);
    }

    /**
     * Retourne l'admin connecté (ou null).
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id'    => $_SESSION['admin_id'],
            'email' => $_SESSION['admin_email'],
            'nom'   => $_SESSION['admin_nom'],
            'role'  => $_SESSION['admin_role'],
        ];
    }

    /**
     * Vérifie qu'un admin a un rôle donné (ou supérieur).
     */
    public static function hasRole(string ...$roles): bool
    {
        $user = self::user();
        if (!$user) return false;
        return in_array($user['role'], $roles, true);
    }

    /**
     * Déconnexion complète.
     */
    public static function logout(): void
    {
        self::$live = null;
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}