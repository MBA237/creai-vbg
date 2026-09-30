<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Demandes d'adhésion à la communauté d'entraide « Solen » (page /solen.php).
 * Données sensibles : téléphone de la personne ET d'un(e) proche de confiance, date de
 * naissance. Ne jamais enregistrer l'adresse IP, ne jamais les exposer côté public.
 */
class SolenAdhesion
{
    public const SITUATIONS = [
        'soutien'            => "J'ai besoin d'écoute, de soutien ou d'aide.",
        'engagement'         => "J'aimerais offrir de mon temps et m'engager aux côtés de l'association.",
        'soutien_engagement' => "J'ai besoin de soutien et j'aimerais également m'engager dans l'action.",
    ];

    public const STATUTS = ['nouveau', 'contacte', 'integre', 'clos'];

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /**
     * Enregistre une demande d'adhésion.
     * @param array $d prenom, nom, pseudo, email, telephone, telephone_proche, date_naissance,
     *                  profession, ville, situation, accepte_confidentialite, accepte_charte,
     *                  accepte_whatsapp, accepte_newsletter
     */
    public function create(array $d): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO solen_adhesions
                (prenom, nom, pseudo, email, telephone, telephone_proche, date_naissance,
                 profession, ville, situation, accepte_confidentialite, accepte_charte,
                 accepte_whatsapp, accepte_newsletter)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $pseudo     = $d['pseudo'] !== '' ? $d['pseudo'] : null;
        $profession = $d['profession'] !== '' ? $d['profession'] : null;

        $confidentialite = !empty($d['accepte_confidentialite']) ? 1 : 0;
        $charte           = !empty($d['accepte_charte']) ? 1 : 0;
        $whatsapp         = !empty($d['accepte_whatsapp']) ? 1 : 0;
        $newsletter       = !empty($d['accepte_newsletter']) ? 1 : 0;

        $stmt->bind_param(
            'ssssssssssiiii',
            $d['prenom'], $d['nom'], $pseudo, $d['email'], $d['telephone'], $d['telephone_proche'],
            $d['date_naissance'], $profession, $d['ville'], $d['situation'],
            $confidentialite, $charte, $whatsapp, $newsletter
        );

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /** Les plus récentes d'abord. Filtre optionnel sur le statut. */
    public function getAll(?string $statut = null): array
    {
        if ($statut !== null && in_array($statut, self::STATUTS, true)) {
            $stmt = $this->conn->prepare(
                "SELECT * FROM solen_adhesions WHERE statut = ? ORDER BY created_at DESC, id DESC"
            );
            $stmt->bind_param('s', $statut);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        return $this->conn->query("SELECT * FROM solen_adhesions ORDER BY created_at DESC, id DESC")
            ->fetch_all(MYSQLI_ASSOC);
    }

    /** ['nouveau' => n, 'contacte' => n, 'integre' => n, 'clos' => n] */
    public function countByStatut(): array
    {
        $counts = array_fill_keys(self::STATUTS, 0);
        $result = $this->conn->query("SELECT statut, COUNT(*) AS n FROM solen_adhesions GROUP BY statut");
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            $counts[$row['statut']] = (int) $row['n'];
        }
        return $counts;
    }

    public function setStatut(int $id, string $statut): bool
    {
        if (!in_array($statut, self::STATUTS, true)) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE solen_adhesions SET statut = ? WHERE id = ?");
        $stmt->bind_param('si', $statut, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM solen_adhesions WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
