<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * Chiffres et activité récente du tableau de bord admin.
 *
 * Une requête agrégée par indicateur (jamais de getAll() pour compter). Chaque source est
 * isolée : si une table manque (migration pas encore jouée), elle compte 0 au lieu de faire
 * tomber tout le tableau de bord.
 *
 * Les sources sensibles (signalements, Solen) ne sont lues que si l'appelant l'autorise
 * explicitement ($sensitive = true) : c'est au tableau de bord de décider selon le rôle.
 */
class DashboardStats
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /** Un COUNT scalaire ; 0 si la table n'existe pas. */
    private function scalar(string $sql): int
    {
        try {
            $result = $this->conn->query($sql);
            return $result ? (int) $result->fetch_row()[0] : 0;
        } catch (Throwable $e) {
            return 0;
        }
    }

    /** Lignes d'une requête ; tableau vide si la table n'existe pas. */
    private function rows(string $sql): array
    {
        try {
            $result = $this->conn->query($sql);
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Répartition d'une colonne de statut : ['brouillon' => n, 'publie' => n].
     * $table et $column viennent du code, jamais de l'utilisateur.
     */
    private function groupCount(string $table, string $column): array
    {
        $out = [];
        foreach ($this->rows("SELECT `$column` AS k, COUNT(*) AS n FROM `$table` GROUP BY `$column`") as $r) {
            $out[(string) $r['k']] = (int) $r['n'];
        }
        return $out;
    }

    /**
     * Contenu du site : publié / brouillon par module, événements à venir, équipe.
     * @return array{articles: array, evenements: array, apprentissages: array, partenaires: array, equipe: int}
     */
    public function contenu(): array
    {
        $statuts = static fn (array $g): array => [
            'publie'    => $g['publie'] ?? 0,
            'brouillon' => $g['brouillon'] ?? 0,
        ];

        $evenements = $statuts($this->groupCount('evenements', 'statut'));
        $evenements['a_venir'] = $this->scalar(
            "SELECT COUNT(*) FROM evenements WHERE statut = 'publie' AND date_debut >= NOW()"
        );

        return [
            'articles'       => $statuts($this->groupCount('articles', 'statut')),
            'evenements'     => $evenements,
            'apprentissages' => $statuts($this->groupCount('apprentissages', 'statut')),
            'partenaires'    => $statuts($this->groupCount('partenaires', 'statut')),
            'equipe'         => $this->scalar("SELECT COUNT(*) FROM membres"),
        ];
    }

    /**
     * Communauté : messages, bénévoles, et — si autorisé — demandes Solen.
     * @return array{contacts_total: int, contacts_non_lus: int, benevoles_total: int, benevoles_non_lus: int, solen: array}
     */
    public function communaute(bool $sensitive = false): array
    {
        $solen = array_fill_keys(['nouveau', 'contacte', 'integre', 'clos'], 0);
        if ($sensitive) {
            foreach ($this->groupCount('solen_adhesions', 'statut') as $statut => $n) {
                if (array_key_exists($statut, $solen)) {
                    $solen[$statut] = $n;
                }
            }
        }

        return [
            'contacts_total'    => $this->scalar("SELECT COUNT(*) FROM contacts"),
            'contacts_non_lus'  => $this->scalar("SELECT COUNT(*) FROM contacts WHERE lu = 0"),
            'benevoles_total'   => $this->scalar("SELECT COUNT(*) FROM benevoles"),
            'benevoles_non_lus' => $this->scalar("SELECT COUNT(*) FROM benevoles WHERE lu = 0"),
            'solen'             => $solen,
        ];
    }

    /**
     * Activité récente, toutes sources confondues, la plus récente d'abord.
     * Chaque entrée : type, titre, détail, url, date, age (secondes), nouveau (à traiter), urgent.
     * Aucun contenu de message ni de signalement n'est exposé : titre et détail seulement.
     */
    public function activity(bool $sensitive = false, int $limit = 8): array
    {
        $feed = [];

        // age_s : ancienneté calculée par MySQL (created_at et NOW() dans le même fuseau),
        // donc juste quel que soit le fuseau de PHP.
        foreach ($this->rows("SELECT id, nom, sujet, lu, created_at, TIMESTAMPDIFF(SECOND, created_at, NOW()) AS age_s FROM contacts ORDER BY created_at DESC, id DESC LIMIT 5") as $r) {
            $feed[] = [
                'type'    => 'contact',
                'titre'   => $r['nom'],
                'detail'  => 'Message : ' . $r['sujet'],
                'url'     => '/admin/contacts.php?msg=' . (int) $r['id'],
                'date'    => $r['created_at'],
                'age'     => (int) $r['age_s'],
                'nouveau' => (int) $r['lu'] === 0,
                'urgent'  => false,
            ];
        }

        foreach ($this->rows("SELECT id, nom, categorie, statut, created_at, TIMESTAMPDIFF(SECOND, created_at, NOW()) AS age_s FROM memberships ORDER BY created_at DESC, id DESC LIMIT 5") as $r) {
            $feed[] = [
                'type'    => 'adhesion',
                'titre'   => $r['nom'],
                'detail'  => "Demande d'adhésion",
                'url'     => '/admin/adhesions.php?id=' . (int) $r['id'],
                'date'    => $r['created_at'],
                'age'     => (int) $r['age_s'],
                'nouveau' => $r['statut'] === 'en_attente',
                'urgent'  => false,
            ];
        }

        foreach ($this->rows("SELECT id, prenom, nom, lu, created_at, TIMESTAMPDIFF(SECOND, created_at, NOW()) AS age_s FROM benevoles ORDER BY created_at DESC, id DESC LIMIT 5") as $r) {
            $feed[] = [
                'type'    => 'benevole',
                'titre'   => trim($r['prenom'] . ' ' . $r['nom']),
                'detail'  => 'Candidature bénévole',
                'url'     => '/admin/benevoles.php?id=' . (int) $r['id'],
                'date'    => $r['created_at'],
                'age'     => (int) $r['age_s'],
                'nouveau' => (int) $r['lu'] === 0,
                'urgent'  => false,
            ];
        }

        if ($sensitive) {
            foreach ($this->rows("SELECT id, prenom, statut, created_at, TIMESTAMPDIFF(SECOND, created_at, NOW()) AS age_s FROM solen_adhesions ORDER BY created_at DESC, id DESC LIMIT 5") as $r) {
                $feed[] = [
                    'type'    => 'solen',
                    'titre'   => $r['prenom'],
                    'detail'  => 'Demande Solen',
                    'url'     => '/admin/solen.php?id=' . (int) $r['id'],
                    'date'    => $r['created_at'],
                    'age'     => (int) $r['age_s'],
                    'nouveau' => $r['statut'] === 'nouveau',
                    'urgent'  => false,
                ];
            }

            foreach ($this->rows("SELECT id, reference, danger_immediat, statut, created_at, TIMESTAMPDIFF(SECOND, created_at, NOW()) AS age_s FROM signalements ORDER BY created_at DESC, id DESC LIMIT 5") as $r) {
                $feed[] = [
                    'type'    => 'signalement',
                    'titre'   => $r['reference'],
                    'detail'  => (int) $r['danger_immediat'] === 1 ? 'Signalement — danger immédiat' : 'Signalement',
                    'url'     => '/admin/signalements.php?id=' . (int) $r['id'],
                    'date'    => $r['created_at'],
                    'age'     => (int) $r['age_s'],
                    'nouveau' => $r['statut'] === 'nouveau',
                    'urgent'  => (int) $r['danger_immediat'] === 1,
                ];
            }
        }

        // Le plus récent d'abord = l'ancienneté la plus faible
        usort($feed, static fn (array $a, array $b): int => $a['age'] <=> $b['age']);

        return array_slice($feed, 0, max(1, $limit));
    }
}
