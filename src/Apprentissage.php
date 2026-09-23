<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Liens d'apprentissage (Centre d'apprentissage) : créés par l'admin,
 * affichés sur /apprentissage.php une fois publiés.
 */
class Apprentissage
{
    /** Types alignés sur les 5 formats présentés sur la page publique. */
    public const TYPES = [
        'ressource'   => 'Ressource',
        'webinaire'   => 'Webinaire',
        'cours'       => 'Cours gratuit',
        'pairs'       => 'Échange entre pairs',
        'opportunite' => 'Opportunité',
    ];

    public const LANGUES = ['fr' => 'Français', 'en' => 'English'];

    public const STATUTS = ['brouillon', 'publie'];

    /**
     * Langues d'une fiche : la base stocke 'fr', 'en' ou 'fr,en' (colonne SET).
     * Accepte un tableau (formulaire) ou une chaîne (base) ; retourne les codes valides, dans l'ordre fr, en.
     */
    public static function parseLangues(array|string|null $value): array
    {
        $given = is_array($value) ? $value : explode(',', (string) $value);
        $given = array_map('trim', array_map('strval', $given));
        return array_values(array_intersect(array_keys(self::LANGUES), $given));
    }

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /**
     * Normalise et valide un lien de formation : http(s) uniquement.
     * Retourne l'URL propre, ou null si elle est invalide (ex. javascript:).
     */
    public static function cleanUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '' || strlen($url) > 500) {
            return null;
        }

        // « www.exemple.org/cours » saisi sans https:// : on l'ajoute
        if (!preg_match('#^[a-z][a-z0-9+.-]*:#i', $url) && preg_match('#^[\w-]+(\.[\w-]+)+(/|$|\?|\#)#u', $url)) {
            $url = 'https://' . $url;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        if (!$parts || !in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true) || empty($parts['host'])) {
            return null;
        }

        return $url;
    }

    /** Liens publiés, les plus récents d'abord. Filtre optionnel par type. */
    public function getPublished(?string $type = null): array
    {
        $order = "ORDER BY COALESCE(publie_le, created_at) DESC, id DESC";

        if ($type !== null && isset(self::TYPES[$type])) {
            $stmt = $this->conn->prepare("SELECT * FROM apprentissages WHERE statut = 'publie' AND type = ? $order");
            $stmt->bind_param('s', $type);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        return $this->conn
            ->query("SELECT * FROM apprentissages WHERE statut = 'publie' $order")
            ->fetch_all(MYSQLI_ASSOC);
    }

    /** Nombre de liens publiés par type : ['webinaire' => 3, ...] (types absents = 0). */
    public function countPublishedByType(): array
    {
        $counts = array_fill_keys(array_keys(self::TYPES), 0);
        $result = $this->conn->query("SELECT type, COUNT(*) AS n FROM apprentissages WHERE statut = 'publie' GROUP BY type");
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            $counts[$row['type']] = (int) $row['n'];
        }
        return $counts;
    }

    /** Tous les liens (admin). */
    public function getAll(): array
    {
        return $this->conn
            ->query("SELECT * FROM apprentissages ORDER BY COALESCE(publie_le, created_at) DESC, id DESC")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM apprentissages WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO apprentissages (titre, description, type, langue, source, url, date_formation, statut, publie_le)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'sssssssss',
            $d['titre'], $d['description'], $d['type'], $d['langue'], $d['source'],
            $d['url'], $d['date_formation'], $d['statut'], $d['publie_le']
        );
        $stmt->execute();
        $id = $this->conn->insert_id;
        $stmt->close();
        return $id;
    }

    public function update(int $id, array $d): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE apprentissages
             SET titre = ?, description = ?, type = ?, langue = ?, source = ?,
                 url = ?, date_formation = ?, statut = ?, publie_le = ?
             WHERE id = ?"
        );
        $stmt->bind_param(
            'sssssssssi',
            $d['titre'], $d['description'], $d['type'], $d['langue'], $d['source'],
            $d['url'], $d['date_formation'], $d['statut'], $d['publie_le'], $id
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Publie ou repasse en brouillon (la date de première publication est conservée). */
    public function setStatut(int $id, string $statut): bool
    {
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE apprentissages
             SET statut = ?,
                 publie_le = IF(? = 'publie' AND publie_le IS NULL, NOW(), publie_le)
             WHERE id = ?"
        );
        $stmt->bind_param('ssi', $statut, $statut, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM apprentissages WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
