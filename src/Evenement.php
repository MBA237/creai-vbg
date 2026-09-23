<?php
declare(strict_types=1);

if (!class_exists('Database')) {
    require_once __DIR__ . '/Database.php';
}

if (!class_exists('Evenement')) {

    class Evenement
    {
        private mysqli $conn;

        public function __construct()
        {
            $this->conn = (new Database())->connect();
        }

        /**
         * Événements à venir publiés, du plus proche au plus lointain.
         */
        public function getUpcoming(int $limit = 4): array
        {
            $stmt = $this->conn->prepare(
                "SELECT * FROM evenements
                 WHERE statut = 'publie' AND date_debut >= NOW()
                 ORDER BY date_debut ASC
                 LIMIT ?"
            );
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        /**
         * Événements récents publiés, triés par date desc.
         * Utilisé pour le slider d'accueil.
         */
        public function getRecent(int $limit = 5): array
        {
            $stmt = $this->conn->prepare(
                "SELECT * FROM evenements
                 WHERE statut = 'publie'
                 ORDER BY date_debut DESC
                 LIMIT ?"
            );
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        /**
         * Tous les événements (admin).
         */
        public function getAll(): array
        {
            return $this->conn->query(
                "SELECT * FROM evenements ORDER BY date_debut DESC"
            )->fetch_all(MYSQLI_ASSOC);
        }

        public function find(int $id): ?array
        {
            $stmt = $this->conn->prepare("SELECT * FROM evenements WHERE id = ? LIMIT 1");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $row ?: null;
        }

        public function findBySlug(string $slug): ?array
        {
            $stmt = $this->conn->prepare(
                "SELECT * FROM evenements WHERE slug = ? AND statut = 'publie' LIMIT 1"
            );
            $stmt->bind_param('s', $slug);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $row ?: null;
        }

        public function create(array $data): int
        {
            $stmt = $this->conn->prepare(
                "INSERT INTO evenements (titre, slug, description, contenu, image, lieu, date_debut, date_fin, organisateur, statut)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                'ssssssssss',
                $data['titre'],
                $data['slug'],
                $data['description'],
                $data['contenu'],
                $data['image'],
                $data['lieu'],
                $data['date_debut'],
                $data['date_fin'],
                $data['organisateur'],
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
                "UPDATE evenements
                 SET titre = ?, slug = ?, description = ?, contenu = ?, image = ?,
                     lieu = ?, date_debut = ?, date_fin = ?, organisateur = ?, statut = ?
                 WHERE id = ?"
            );
            $stmt->bind_param(
                'ssssssssssi',
                $data['titre'],
                $data['slug'],
                $data['description'],
                $data['contenu'],
                $data['image'],
                $data['lieu'],
                $data['date_debut'],
                $data['date_fin'],
                $data['organisateur'],
                $data['statut'],
                $id
            );
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

        public function delete(int $id): bool
        {
            $stmt = $this->conn->prepare("DELETE FROM evenements WHERE id = ?");
            $stmt->bind_param('i', $id);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

        public function count(): int
        {
            $result = $this->conn->query("SELECT COUNT(*) AS n FROM evenements");
            return (int) ($result->fetch_assoc()['n'] ?? 0);
        }

        /**
 * Recherche + filtres + pagination.
 */
public function search(
    string $q = '',
    int $page = 1,
    int $perPage = 9
): array {
    $where = ["statut = 'publie'"];
    $params = [];
    $types = '';

    if ($q !== '') {
        $where[] = "(titre LIKE ? OR description LIKE ? OR contenu LIKE ? OR lieu LIKE ?)";
        $like = '%' . $q . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $types .= 'ssss';
    }

    $whereSql = implode(' AND ', $where);

    // Total
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS n FROM evenements WHERE $whereSql");
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $total = (int) ($stmt->get_result()->fetch_assoc()['n'] ?? 0);
    $stmt->close();

    // Pages
    $pages = max(1, (int) ceil($total / $perPage));
    $page  = max(1, min($page, $pages));
    $offset = ($page - 1) * $perPage;

    // Items
    $sql = "SELECT * FROM evenements
            WHERE $whereSql
            ORDER BY date_debut DESC
            LIMIT ? OFFSET ?";

    $stmt = $this->conn->prepare($sql);
    $paramsWithLimit = $params;
    $paramsWithLimit[] = $perPage;
    $paramsWithLimit[] = $offset;
    $typesWithLimit = $types . 'ii';

    $stmt->bind_param($typesWithLimit, ...$paramsWithLimit);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return ['items' => $items, 'total' => $total, 'pages' => $pages];
}
    }

    
}