<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class Membership
{
    public const STATUTS = ['en_attente', 'agree', 'refuse'];

    public const CATEGORIES = [
        'actif'        => 'Membre actif',
        'sympathisant' => 'Membre sympathisant',
        'bienfaiteur'  => 'Membre bienfaiteur',
        'partenaire'   => 'Partenaire institutionnel',
    ];

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function create(string $nom, string $email, string $categorie, string $message): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO memberships (nom, email, categorie, message) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('ssss', $nom, $email, $categorie, $message);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Demandes d'adhésion, les plus récentes d'abord. Filtre optionnel sur le statut. */
    public function getAll(?string $statut = null): array
    {
        if ($statut !== null && in_array($statut, self::STATUTS, true)) {
            $stmt = $this->conn->prepare(
                "SELECT * FROM memberships WHERE statut = ? ORDER BY created_at DESC, id DESC"
            );
            $stmt->bind_param('s', $statut);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        $result = $this->conn->query("SELECT * FROM memberships ORDER BY created_at DESC, id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /** Dernières demandes reçues, tous statuts confondus. */
    public function getRecent(int $limit = 6): array
    {
        $limit = max(1, $limit);
        $stmt = $this->conn->prepare("SELECT * FROM memberships ORDER BY created_at DESC, id DESC LIMIT ?");
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /** Nombre de demandes par statut : ['en_attente' => n, 'agree' => n, 'refuse' => n]. */
    public function countByStatut(): array
    {
        $counts = array_fill_keys(self::STATUTS, 0);
        $result = $this->conn->query("SELECT statut, COUNT(*) AS n FROM memberships GROUP BY statut");
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            $counts[$row['statut']] = (int) $row['n'];
        }
        return $counts;
    }

    public function countPending(): int
    {
        return $this->countByStatut()['en_attente'];
    }

    /** Change le statut d'une demande. « En attente » efface la date de décision. */
    public function setStatut(int $id, string $statut): bool
    {
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }

        $stmt = $this->conn->prepare(
            "UPDATE memberships
             SET statut = ?, traite_at = IF(? = 'en_attente', NULL, NOW())
             WHERE id = ?"
        );
        $stmt->bind_param('ssi', $statut, $statut, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM memberships WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
