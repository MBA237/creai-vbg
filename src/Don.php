<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Dons déclarés sur /don.php. Le site n'encaisse pas : l'équipe passe le statut à « reçu »
 * une fois le règlement constaté (carte, PayPal, Mobile Money ou virement).
 */
class Don
{
    public const STATUTS = ['en_attente', 'recu', 'annule'];

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /**
     * Enregistre un don et renvoie sa référence (à communiquer au donateur), ou null en cas d'échec.
     * @param array $d mode, montant, moyen, mobile_operateur, mobile_numero, prenom, nom,
     *                  email, telephone, organisation, adresse
     */
    public function create(array $d): ?string
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO dons
                (reference, mode, montant, moyen, mobile_operateur, mobile_numero,
                 prenom, nom, email, telephone, organisation, adresse)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $reference = 'DON-' . strtoupper(bin2hex(random_bytes(4)));
        $montant   = (float) $d['montant'];
        $operateur = $d['mobile_operateur'] !== '' ? $d['mobile_operateur'] : null;
        $numero    = $d['mobile_numero'] !== '' ? $d['mobile_numero'] : null;
        $adresse   = $d['adresse'] !== '' ? $d['adresse'] : null;
        $orga      = !empty($d['organisation']) ? 1 : 0;

        $stmt->bind_param(
            'ssdsssssssis',
            $reference, $d['mode'], $montant, $d['moyen'], $operateur, $numero,
            $d['prenom'], $d['nom'], $d['email'], $d['telephone'], $orga, $adresse
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok ? $reference : null;
    }


    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM dons WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** Les plus récents d'abord. Filtre optionnel sur le statut. */
    public function getAll(?string $statut = null): array
    {
        if ($statut !== null && in_array($statut, self::STATUTS, true)) {
            $stmt = $this->conn->prepare(
                "SELECT * FROM dons WHERE statut = ? ORDER BY created_at DESC, id DESC"
            );
            $stmt->bind_param('s', $statut);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        return $this->conn->query("SELECT * FROM dons ORDER BY created_at DESC, id DESC")
            ->fetch_all(MYSQLI_ASSOC);
    }

    /** ['en_attente' => n, 'recu' => n, 'annule' => n] */
    public function countByStatut(): array
    {
        $counts = array_fill_keys(self::STATUTS, 0);
        $result = $this->conn->query("SELECT statut, COUNT(*) AS n FROM dons GROUP BY statut");
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            $counts[$row['statut']] = (int) $row['n'];
        }
        return $counts;
    }

    /** Somme des montants au statut « reçu » (un don mensuel compte pour un seul versement). */
    public function totalRecu(): float
    {
        $row = $this->conn->query("SELECT COALESCE(SUM(montant), 0) AS t FROM dons WHERE statut = 'recu'")
            ->fetch_assoc();
        return (float) ($row['t'] ?? 0);
    }

    public function setStatut(int $id, string $statut): bool
    {
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE dons SET statut = ? WHERE id = ?");
        $stmt->bind_param('si', $statut, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM dons WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
