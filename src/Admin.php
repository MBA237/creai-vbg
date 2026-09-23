<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Gestion des comptes administrateurs (création, modification, désactivation, suppression).
 * La connexion et la session sont gérées par AdminAuth.
 */
class Admin
{
    public const ROLES = [
        'super_admin' => 'Super administrateur',
        'admin'       => 'Administrateur',
        'editeur'     => 'Éditeur',
    ];

    /** Ce que chaque rôle permet réellement (voir les contrôles dans l'espace admin). */
    public const ROLE_DESCRIPTIONS = [
        'super_admin' => 'Accès complet, y compris la gestion des comptes administrateurs et les signalements.',
        'admin'       => 'Gère tout le contenu, les messages, les adhésions et les signalements. Ne gère pas les comptes admin.',
        'editeur'     => 'Gère le contenu, les messages et les adhésions. Pas d\'accès aux signalements ni aux comptes admin.',
    ];

    public const MIN_PASSWORD = 12;

    private const COMMON_PASSWORDS = [
        'motdepasse123', 'motdepasse1234', 'password1234', 'password12345', 'azertyuiop12',
        'azertyuiop123', 'qwertyuiop12', '123456789012', 'administrateur1', 'creaivbg1234',
    ];

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /** Argon2id si l'hébergeur le propose, sinon l'algorithme par défaut de PHP (bcrypt). */
    public static function hashAlgo(): string|int
    {
        return defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, self::hashAlgo());
    }

    /**
     * Règles de mot de passe. Retourne un message d'erreur, ou null si le mot de passe est accepté.
     */
    public static function validatePassword(string $password, string $email = '', string $nom = ''): ?string
    {
        $len = mb_strlen($password);

        if ($len < self::MIN_PASSWORD) {
            return 'Le mot de passe doit faire au moins ' . self::MIN_PASSWORD . ' caractères.';
        }
        if ($len > 200) {
            return 'Le mot de passe est trop long (200 caractères maximum).';
        }
        if (!preg_match('/\p{L}/u', $password) || !preg_match('/\d/', $password)) {
            return 'Le mot de passe doit contenir au moins une lettre et un chiffre.';
        }

        $lower = mb_strtolower($password);
        if (in_array($lower, self::COMMON_PASSWORDS, true)) {
            return 'Ce mot de passe est trop courant : choisissez-en un autre.';
        }

        // Ne doit pas reprendre l'identifiant ou le nom
        $local = mb_strtolower(explode('@', $email)[0] ?? '');
        if (mb_strlen($local) >= 4 && str_contains($lower, $local)) {
            return 'Le mot de passe ne doit pas contenir l\'identifiant de l\'adresse email.';
        }
        $nomLower = mb_strtolower(trim($nom));
        if (mb_strlen($nomLower) >= 4 && str_contains($lower, $nomLower)) {
            return 'Le mot de passe ne doit pas contenir le nom du compte.';
        }

        return null;
    }

    /** Colonnes exposées : jamais le hash du mot de passe. */
    private const COLS = 'id, email, nom, role, actif, doit_changer_mdp, derniere_connexion, tentatives_echecs, bloque_jusqua, created_at';

    public function getAll(): array
    {
        return $this->conn
            ->query('SELECT ' . self::COLS . " FROM admins ORDER BY actif DESC, FIELD(role, 'super_admin', 'admin', 'editeur'), nom ASC")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare('SELECT ' . self::COLS . ' FROM admins WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Hash du mot de passe d'un compte (pour vérifier « mot de passe actuel »). */
    public function passwordHash(int $id): ?string
    {
        $stmt = $this->conn->prepare('SELECT password_hash FROM admins WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row['password_hash'] ?? null;
    }

    public function emailExists(string $email, int $excludeId = 0): bool
    {
        $stmt = $this->conn->prepare('SELECT 1 FROM admins WHERE email = ? AND id != ? LIMIT 1');
        $stmt->bind_param('si', $email, $excludeId);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /** Nombre de super administrateurs actifs, en ignorant éventuellement un compte. */
    public function countActiveSuperAdmins(int $excludeId = 0): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM admins WHERE role = 'super_admin' AND actif = 1 AND id != ?");
        $stmt->bind_param('i', $excludeId);
        $stmt->execute();
        $n = (int) $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        return $n;
    }

    /** Crée un compte. Le nouveau titulaire devra changer son mot de passe à sa première connexion. */
    public function create(string $nom, string $email, string $role, string $passwordHash): int
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO admins (email, password_hash, nom, role, actif, doit_changer_mdp) VALUES (?, ?, ?, ?, 1, 1)'
        );
        $stmt->bind_param('ssss', $email, $passwordHash, $nom, $role);
        $stmt->execute();
        $id = $this->conn->insert_id;
        $stmt->close();
        return $id;
    }

    public function update(int $id, string $nom, string $email, string $role, bool $actif): bool
    {
        $a = $actif ? 1 : 0;
        $stmt = $this->conn->prepare('UPDATE admins SET nom = ?, email = ?, role = ?, actif = ? WHERE id = ?');
        $stmt->bind_param('sssii', $nom, $email, $role, $a, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function setActif(int $id, bool $actif): bool
    {
        $a = $actif ? 1 : 0;
        $stmt = $this->conn->prepare('UPDATE admins SET actif = ? WHERE id = ?');
        $stmt->bind_param('ii', $a, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Définit un nouveau mot de passe et lève un éventuel blocage.
     * $mustChange = true : la personne devra en choisir un autre à sa prochaine connexion.
     */
    public function setPassword(int $id, string $passwordHash, bool $mustChange): bool
    {
        $m = $mustChange ? 1 : 0;
        $stmt = $this->conn->prepare(
            'UPDATE admins SET password_hash = ?, doit_changer_mdp = ?, tentatives_echecs = 0, bloque_jusqua = NULL WHERE id = ?'
        );
        $stmt->bind_param('sii', $passwordHash, $m, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function unblock(int $id): bool
    {
        $stmt = $this->conn->prepare('UPDATE admins SET tentatives_echecs = 0, bloque_jusqua = NULL WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare('DELETE FROM admins WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
