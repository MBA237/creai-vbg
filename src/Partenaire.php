<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Partenaires du CREAI-VBG (institutions, entreprises, organisations).
 * Gérés depuis l'admin ; affichés sur /apropos.php et /partenaires.php.
 */
class Partenaire
{
    public const STATUTS = ['brouillon', 'publie'];

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /** Partenaires visibles sur le site, dans l'ordre choisi par l'admin. */
    public function getPublished(): array
    {
        return $this->conn->query(
            "SELECT * FROM partenaires WHERE statut = 'publie' ORDER BY ordre ASC, nom ASC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Partenaires visibles, une page à la fois.
     *
     * @return array{items:array, total:int, pages:int, page:int}
     */
    public function paginate(int $page = 1, int $perPage = 12): array
    {
        $total = (int) ($this->conn->query(
            "SELECT COUNT(*) AS n FROM partenaires WHERE statut = 'publie'"
        )->fetch_assoc()['n'] ?? 0);

        $pages  = max(1, (int) ceil($total / $perPage));
        $page   = max(1, min($page, $pages));
        $offset = ($page - 1) * $perPage;

        $stmt = $this->conn->prepare(
            "SELECT * FROM partenaires WHERE statut = 'publie'
             ORDER BY ordre ASC, nom ASC
             LIMIT ? OFFSET ?"
        );
        $stmt->bind_param('ii', $perPage, $offset);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return ['items' => $items, 'total' => $total, 'pages' => $pages, 'page' => $page];
    }

    /** Tous les partenaires, brouillons compris (admin). */
    public function getAll(): array
    {
        return $this->conn->query(
            "SELECT * FROM partenaires ORDER BY ordre ASC, nom ASC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM partenaires WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO partenaires (nom, categorie, description, logo, site_web, ordre, statut)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'sssssis',
            $data['nom'],
            $data['categorie'],
            $data['description'],
            $data['logo'],
            $data['site_web'],
            $data['ordre'],
            $data['statut']
        );
        $stmt->execute();
        $id = $this->conn->insert_id;
        $stmt->close();
        return $id;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE partenaires
             SET nom = ?, categorie = ?, description = ?, logo = ?, site_web = ?, ordre = ?, statut = ?
             WHERE id = ?"
        );
        $stmt->bind_param(
            'sssssisi',
            $data['nom'],
            $data['categorie'],
            $data['description'],
            $data['logo'],
            $data['site_web'],
            $data['ordre'],
            $data['statut'],
            $id
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM partenaires WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /**
     * Adresse de site saisie librement → URL http(s) valide, ou null si vide / invalide.
     * « www.exemple.org » devient « https://www.exemple.org ».
     */
    public static function normalizeUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }
        if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('#^https?://[^/\s]+\.[^/\s]+#i', $url)) {
            return null;
        }
        return $url;
    }
}
