<?php
declare(strict_types=1);

if (!class_exists('Database')) {
    require_once __DIR__ . '/Database.php';
}

if (!class_exists('Article')) {

    class Article
    {
        /**
         * Catégories proposées dans l'admin (liste de sélection).
         * Pour en ajouter ou en retirer, il suffit de modifier cette liste.
         */
        public const CATEGORIES = [
            'Recherche',
            'Prévention',
            'Innovation',
            'Accompagnement',
            'Plaidoyer',
            "Vie de l'association",
        ];

        public const STATUTS = ['brouillon', 'publie'];

        private mysqli $conn;

        public function __construct()
        {
            $this->conn = (new Database())->connect();
        }

        /**
         * Articles publiés, triés par date de publication desc.
         * Utilisé pour la page liste et les grilles.
         */
        public function getPublished(int $limit = 6): array
        {
            $stmt = $this->conn->prepare(
                "SELECT a.*, a.auteur AS auteur_nom
                 FROM articles a
                 WHERE a.statut = 'publie'
                 ORDER BY COALESCE(a.publie_le, a.created_at) DESC
                 LIMIT ?"
            );
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        /**
         * Articles récents pour le slider d'accueil.
         * Alias de getPublished() mais avec limite différente.
         */
        public function getRecent(int $limit = 5): array
        {
            return $this->getPublished($limit);
        }

        /**
         * Tous les articles (admin).
         */
        public function getAll(): array
        {
                $sql = "SELECT a.*, a.auteur AS auteur_nom
                    FROM articles a
                    ORDER BY COALESCE(a.publie_le, a.created_at) DESC";
            return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
        }

        public function find(int $id): ?array
        {
            $stmt = $this->conn->prepare("SELECT * FROM articles WHERE id = ? LIMIT 1");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $row ?: null;
        }

        public function findBySlug(string $slug): ?array
        {
            $stmt = $this->conn->prepare(
                "SELECT a.*, a.auteur AS auteur_nom
                 FROM articles a
                 WHERE a.slug = ? AND a.statut = 'publie' LIMIT 1"
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
                "INSERT INTO articles (titre, slug, extrait, contenu, image, auteur, categorie, statut, publie_le)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                'sssssssss',
                $data['titre'],
                $data['slug'],
                $data['extrait'],
                $data['contenu'],
                $data['image'],
                $data['auteur'],
                $data['categorie'],
                $data['statut'],
                $data['publie_le']
            );
            $stmt->execute();
            $id = $this->conn->insert_id;
            $stmt->close();
            return $id;
        }

        public function update(int $id, array $data): bool
        {
            $stmt = $this->conn->prepare(
                "UPDATE articles
                 SET titre = ?, slug = ?, extrait = ?, contenu = ?, image = ?,
                     auteur = ?, categorie = ?, statut = ?, publie_le = ?
                 WHERE id = ?"
            );
            $stmt->bind_param(
                'sssssssssi',
                $data['titre'],
                $data['slug'],
                $data['extrait'],
                $data['contenu'],
                $data['image'],
                $data['auteur'],
                $data['categorie'],
                $data['statut'],
                $data['publie_le'],
                $id
            );
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

        /**
         * Publie ou repasse un article en brouillon.
         * À la première publication, la date de publication est fixée à maintenant ;
         * elle est conservée si l'article est dépublié puis republié.
         */
        public function setStatut(int $id, string $statut): bool
        {
            if (!in_array($statut, self::STATUTS, true)) {
                return false;
            }

            $stmt = $this->conn->prepare(
                "UPDATE articles
                 SET statut = ?,
                     publie_le = IF(? = 'publie' AND publie_le IS NULL, NOW(), publie_le)
                 WHERE id = ?"
            );
            $stmt->bind_param('ssi', $statut, $statut, $id);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

        public function slugExists(string $slug, int $excludeId = 0): bool
        {
            $stmt = $this->conn->prepare("SELECT 1 FROM articles WHERE slug = ? AND id != ? LIMIT 1");
            $stmt->bind_param('si', $slug, $excludeId);
            $stmt->execute();
            $exists = $stmt->get_result()->num_rows > 0;
            $stmt->close();
            return $exists;
        }

        public function delete(int $id): bool
        {
            $stmt = $this->conn->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->bind_param('i', $id);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

        public function count(): int
        {
            $result = $this->conn->query("SELECT COUNT(*) AS n FROM articles");
            return (int) ($result->fetch_assoc()['n'] ?? 0);
        }

        /**
 * Recherche + filtres + pagination.
 *
 * @return array ['items' => [...], 'total' => int, 'pages' => int]
 */
public function search(
    string $q = '',
    string $categorie = '',
    int $page = 1,
    int $perPage = 9
): array {
    $where = ["statut = 'publie'"];
    $params = [];
    $types = '';

    if ($q !== '') {
        $where[] = "(titre LIKE ? OR extrait LIKE ? OR contenu LIKE ?)";
        $like = '%' . $q . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $types .= 'sss';
    }

    if ($categorie !== '') {
        $where[] = "categorie = ?";
        $params[] = $categorie;
        $types .= 's';
    }

    $whereSql = implode(' AND ', $where);

    // Total
    $stmt = $this->conn->prepare("SELECT COUNT(*) AS n FROM articles WHERE $whereSql");
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
        $sql = "SELECT a.*, a.auteur AS auteur_nom
            FROM articles a
            WHERE $whereSql
            ORDER BY COALESCE(a.publie_le, a.created_at) DESC
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

/**
 * Liste des catégories distinctes (pour le filtre).
 */
public function getCategories(): array
{
    $result = $this->conn->query(
        "SELECT DISTINCT categorie FROM articles
         WHERE statut = 'publie' AND categorie IS NOT NULL AND categorie != ''
         ORDER BY categorie ASC"
    );
    return array_column($result->fetch_all(MYSQLI_ASSOC), 'categorie');
}

/**
 * Récupère un article par son slug (tous statuts — pour admin).
 */
public function findBySlugAny(string $slug): ?array
{
    $stmt = $this->conn->prepare(
        "SELECT a.*, a.auteur AS auteur_nom
         FROM articles a
         WHERE a.slug = ? LIMIT 1"
    );
    $stmt->bind_param('s', $slug);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

/**
 * Articles similaires (même catégorie, exclut l'article courant).
 */
public function getRelated(int $excludeId, string $categorie = '', int $limit = 3): array
{
    if ($categorie !== '') {
        $stmt = $this->conn->prepare(
            "SELECT a.*, a.auteur AS auteur_nom
             FROM articles a
             WHERE a.statut = 'publie' AND a.id != ? AND a.categorie = ?
             ORDER BY COALESCE(a.publie_le, a.created_at) DESC
             LIMIT ?"
        );
        $stmt->bind_param('isi', $excludeId, $categorie, $limit);
    } else {
        $stmt = $this->conn->prepare(
            "SELECT a.*, a.auteur AS auteur_nom
             FROM articles a
             WHERE a.statut = 'publie' AND a.id != ?
             ORDER BY COALESCE(a.publie_le, a.created_at) DESC
             LIMIT ?"
        );
        $stmt->bind_param('ii', $excludeId, $limit);
    }

    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $rows;
}

    }
}