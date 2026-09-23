<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Signalements en ligne d'une situation de violence.
 * Données sensibles : ne jamais enregistrer l'adresse IP, ne jamais les exposer côté public.
 */
class Signalement
{
    public const POUR_QUI = [
        'moi'           => 'Je suis concerné(e)',
        'proche'        => 'Une personne de mon entourage est concernée',
        'temoin'        => 'J\'ai été témoin d\'une situation',
        'professionnel' => 'Je signale dans le cadre de mon travail',
    ];

    public const TYPES = [
        'physique'      => 'Violence physique',
        'sexuelle'      => 'Violence sexuelle',
        'psychologique' => 'Violence psychologique ou verbale',
        'economique'    => 'Violence économique',
        'numerique'     => 'Violence en ligne (cyberharcèlement, diffusion d\'images)',
        'harcelement'   => 'Harcèlement',
        'mariage_force' => 'Mariage forcé ou précoce',
        'autre'         => 'Autre / je ne sais pas',
    ];

    public const MINEUR = [
        'oui'     => 'Oui, une personne mineure (moins de 18 ans) est concernée',
        'non'     => 'Non',
        'inconnu' => 'Je ne sais pas',
    ];

    public const CANAUX = [
        'telephone' => 'Appel téléphonique',
        'whatsapp'  => 'WhatsApp',
        'sms'       => 'SMS',
        'email'     => 'Email',
    ];

    public const STATUTS = [
        'nouveau'  => 'Nouveau',
        'en_cours' => 'En cours',
        'traite'   => 'Traité',
    ];

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /** Codes de types valides, dans l'ordre de la liste. */
    public static function parseTypes(array|string|null $value): array
    {
        $given = is_array($value) ? $value : explode(',', (string) $value);
        $given = array_map('trim', array_map('strval', $given));
        return array_values(array_intersect(array_keys(self::TYPES), $given));
    }

    /** Référence lisible, sans caractères ambigus (0/O, 1/I) : SIG-7K3M9QXP. */
    private static function newReference(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $ref = '';
        for ($i = 0; $i < 8; $i++) {
            $ref .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        return 'SIG-' . $ref;
    }

    /**
     * Enregistre un signalement et retourne sa référence.
     * @param array $d pour_qui, danger_immediat, mineur, types[], description, lieu, date_faits,
     *                 contact_nom, contact_moyen, contact_canal, contact_sur
     */
    public function create(array $d): string
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO signalements
                (reference, pour_qui, danger_immediat, mineur, types, description, lieu, date_faits,
                 contact_nom, contact_moyen, contact_canal, contact_sur)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $types  = implode(',', $d['types']);
        $danger = !empty($d['danger_immediat']) ? 1 : 0;
        $sur    = !empty($d['contact_sur']) ? 1 : 0;

        // La référence est unique : en cas (très improbable) de collision, on en tire une autre
        for ($try = 0; $try < 5; $try++) {
            $ref = self::newReference();
            $stmt->bind_param(
                'ssissssssssi',
                $ref, $d['pour_qui'], $danger, $d['mineur'], $types, $d['description'],
                $d['lieu'], $d['date_faits'], $d['contact_nom'], $d['contact_moyen'], $d['contact_canal'], $sur
            );
            try {
                $stmt->execute();
                $stmt->close();
                return $ref;
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() !== 1062) { // 1062 = doublon
                    throw $e;
                }
            }
        }
        throw new RuntimeException('Impossible de générer une référence unique.');
    }

    /** Les nouveaux d'abord, puis danger immédiat, puis les plus récents. */
    public function getAll(?string $statut = null): array
    {
        $order = "ORDER BY (statut = 'nouveau') DESC, danger_immediat DESC, created_at DESC, id DESC";

        if ($statut !== null && isset(self::STATUTS[$statut])) {
            $stmt = $this->conn->prepare("SELECT * FROM signalements WHERE statut = ? $order");
            $stmt->bind_param('s', $statut);
            $stmt->execute();
            $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rows;
        }

        return $this->conn->query("SELECT * FROM signalements $order")->fetch_all(MYSQLI_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM signalements WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /** ['nouveau' => n, 'en_cours' => n, 'traite' => n, 'danger_nouveau' => n] */
    public function counts(): array
    {
        $counts = array_fill_keys(array_keys(self::STATUTS), 0);
        foreach ($this->conn->query("SELECT statut, COUNT(*) AS n FROM signalements GROUP BY statut")->fetch_all(MYSQLI_ASSOC) as $r) {
            $counts[$r['statut']] = (int) $r['n'];
        }
        $counts['danger_nouveau'] = (int) $this->conn
            ->query("SELECT COUNT(*) FROM signalements WHERE statut = 'nouveau' AND danger_immediat = 1")
            ->fetch_row()[0];
        return $counts;
    }

    public function setStatut(int $id, string $statut): bool
    {
        if (!isset(self::STATUTS[$statut])) {
            return false;
        }
        $stmt = $this->conn->prepare("UPDATE signalements SET statut = ? WHERE id = ?");
        $stmt->bind_param('si', $statut, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM signalements WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
