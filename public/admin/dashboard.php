<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Membership.php';
require_once __DIR__ . '/../../src/DashboardStats.php';

// Signalements et Solen : données très sensibles, réservées aux administrateurs
$canSeeSensitive = AdminAuth::hasRole('super_admin', 'admin');

$stats      = new DashboardStats();
$contenu    = $stats->contenu();
$communaute = $stats->communaute($canSeeSensitive);
$activity   = $stats->activity($canSeeSensitive, 8);

$membershipModel  = new Membership();
$adhesionCounts   = $membershipModel->countByStatut();
$pendingAdhesions = $adhesionCounts['en_attente'];
$recentAdhesions  = $membershipModel->getRecent(6);

$adhesionLabels = ['en_attente' => 'En attente', 'agree' => 'Agréée', 'refuse' => 'Refusée'];
$adhesionCats   = Membership::CATEGORIES;

$sigCounts = ['nouveau' => 0, 'danger_nouveau' => 0];
if ($canSeeSensitive) {
    require_once __DIR__ . '/../../src/Signalement.php';
    $sigCounts = (new Signalement())->counts();
}

$solenNouveaux = $communaute['solen']['nouveau'];
$solenIntegres = $communaute['solen']['integre'];

// Ce qui attend une action de l'équipe
$aTraiter = $sigCounts['nouveau']
          + $communaute['contacts_non_lus']
          + $pendingAdhesions
          + $communaute['benevoles_non_lus']
          + $solenNouveaux;

$e = static fn (string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');

/** Pluriel simple : plural(3, 'message') → « messages ». */
$plural = static fn (int $n, string $singular, ?string $plural = null): string
    => $n > 1 ? ($plural ?? $singular . 's') : $singular;

// --- Salutation et date en français (sans dépendre de l'extension intl) ---
$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
$mois  = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août',
          'septembre', 'octobre', 'novembre', 'décembre'];
$dateFr = ucfirst($jours[(int) date('N') - 1]) . ' ' . date('j') . ' ' . $mois[(int) date('n') - 1] . ' ' . date('Y');
$prenom = trim(explode(' ', trim((string) $currentAdmin['nom']))[0]);
$salut  = ((int) date('G') < 18 ? 'Bonjour' : 'Bonsoir') . ($prenom !== '' ? ', ' . $prenom : '');

/** « à l'instant », « il y a 5 min », « hier »… au-delà d'une semaine : la date. */
function dash_ago(int $seconds, string $date): string
{
    if ($seconds < 60)       return "à l'instant";
    if ($seconds < 3600)     return 'il y a ' . intdiv($seconds, 60) . ' min';
    if ($seconds < 86400)    return 'il y a ' . intdiv($seconds, 3600) . ' h';
    if ($seconds < 172800)   return 'hier';
    if ($seconds < 604800)   return 'il y a ' . intdiv($seconds, 86400) . ' j';
    return date('d/m/Y', strtotime($date));
}

// --- Icônes (contenu interne des <svg viewBox="0 0 24 24">) ---
$icons = [
    'alert'    => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
    'mail'     => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
    'user-add' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>',
    'user-ok'  => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/>',
    'users'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    'moon'     => '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>',
    'file'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
    'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
    'cap'      => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
    'partners' => '<path d="M11 17a4 4 0 0 1-8 0V7l4-4 4 4"/><path d="M13 7l4-4 4 4v10a4 4 0 0 1-8 0"/><path d="M9 12h6"/>',
    'external' => '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/>',
    'check'    => '<polyline points="20 6 9 17 4 12"/>',
    'plus'     => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
];
$svg = static fn (string $name, int $size = 24): string
    => '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
     . ($icons[$name] ?? '') . '</svg>';

// --- Cartes « À traiter » : n'apparaissent en alerte que si quelque chose attend ---
$todo = [];

if ($canSeeSensitive) {
    $todo[] = [
        'href'  => '/admin/signalements.php?statut=nouveau',
        'icon'  => 'alert', 'tone' => 'coral',
        'n'     => $sigCounts['nouveau'],
        'label' => $plural($sigCounts['nouveau'], 'Signalement à lire', 'Signalements à lire'),
        'badge' => $sigCounts['danger_nouveau'] > 0 ? $sigCounts['danger_nouveau'] . ' en danger immédiat' : null,
        'urgent' => $sigCounts['danger_nouveau'] > 0,
    ];
}

$todo[] = [
    'href'  => '/admin/contacts.php',
    'icon'  => 'mail', 'tone' => 'teal',
    'n'     => $communaute['contacts_non_lus'],
    'label' => $plural($communaute['contacts_non_lus'], 'Message non lu', 'Messages non lus'),
    'badge' => null, 'urgent' => false,
];

$todo[] = [
    'href'  => '/admin/adhesions.php?statut=en_attente',
    'icon'  => 'user-add', 'tone' => 'coral',
    'n'     => $pendingAdhesions,
    'label' => $plural($pendingAdhesions, "Adhésion à examiner", "Adhésions à examiner"),
    'badge' => null, 'urgent' => false,
];

$todo[] = [
    'href'  => '/admin/benevoles.php',
    'icon'  => 'users', 'tone' => 'purple',
    'n'     => $communaute['benevoles_non_lus'],
    'label' => $plural($communaute['benevoles_non_lus'], 'Candidature bénévole', 'Candidatures bénévoles'),
    'badge' => null, 'urgent' => false,
];

if ($canSeeSensitive) {
    $todo[] = [
        'href'  => '/admin/solen.php?statut=nouveau',
        'icon'  => 'moon', 'tone' => 'purple',
        'n'     => $solenNouveaux,
        'label' => $plural($solenNouveaux, 'Demande Solen', 'Demandes Solen'),
        'badge' => null, 'urgent' => false,
    ];
}

// --- Cartes « Vue d'ensemble » ---
$overview = [
    [
        'href' => '/admin/articles.php', 'icon' => 'file', 'tone' => 'teal',
        'n' => $contenu['articles']['publie'],
        'label' => $plural($contenu['articles']['publie'], 'Actualité publiée', 'Actualités publiées'),
        'sub' => $contenu['articles']['brouillon'] > 0
            ? $contenu['articles']['brouillon'] . ' ' . $plural($contenu['articles']['brouillon'], 'brouillon') : null,
    ],
    [
        'href' => '/admin/evenements.php', 'icon' => 'calendar', 'tone' => 'teal',
        'n' => $contenu['evenements']['a_venir'],
        'label' => $plural($contenu['evenements']['a_venir'], 'Événement à venir', 'Événements à venir'),
        'sub' => implode(' · ', array_filter([
            $contenu['evenements']['publie'] > 0
                ? $contenu['evenements']['publie'] . ' ' . $plural($contenu['evenements']['publie'], 'publié') . ' au total' : null,
            $contenu['evenements']['brouillon'] > 0
                ? $contenu['evenements']['brouillon'] . ' ' . $plural($contenu['evenements']['brouillon'], 'brouillon') : null,
        ])) ?: null,
    ],
    [
        'href' => '/admin/apprentissages.php', 'icon' => 'cap', 'tone' => 'purple',
        'n' => $contenu['apprentissages']['publie'],
        'label' => $plural($contenu['apprentissages']['publie'], "Ressource d'apprentissage", "Ressources d'apprentissage"),
        'sub' => $contenu['apprentissages']['brouillon'] > 0
            ? $contenu['apprentissages']['brouillon'] . ' ' . $plural($contenu['apprentissages']['brouillon'], 'brouillon') : null,
    ],
    [
        'href' => '/admin/partenaires.php', 'icon' => 'partners', 'tone' => 'purple',
        'n' => $contenu['partenaires']['publie'],
        'label' => $plural($contenu['partenaires']['publie'], 'Partenaire publié', 'Partenaires publiés'),
        'sub' => $contenu['partenaires']['brouillon'] > 0
            ? $contenu['partenaires']['brouillon'] . ' ' . $plural($contenu['partenaires']['brouillon'], 'brouillon') : null,
    ],
    [
        'href' => '/admin/adhesions.php?statut=agree', 'icon' => 'user-ok', 'tone' => 'teal',
        'n' => $adhesionCounts['agree'],
        'label' => $plural($adhesionCounts['agree'], 'Adhérent agréé', 'Adhérents agréés'),
        'sub' => null,
    ],
    [
        'href' => '/admin/membres.php', 'icon' => 'users', 'tone' => 'purple',
        'n' => $contenu['equipe'],
        'label' => $plural($contenu['equipe'], "Membre de l'équipe", "Membres de l'équipe"),
        'sub' => null,
    ],
    [
        'href' => '/admin/benevoles.php', 'icon' => 'users', 'tone' => 'gray',
        'n' => $communaute['benevoles_total'],
        'label' => $plural($communaute['benevoles_total'], 'Candidature bénévole reçue', 'Candidatures bénévoles reçues'),
        'sub' => null,
    ],
    [
        'href' => '/admin/contacts.php', 'icon' => 'mail', 'tone' => 'gray',
        'n' => $communaute['contacts_total'],
        'label' => $plural($communaute['contacts_total'], 'Message reçu', 'Messages reçus'),
        'sub' => null,
    ],
];

if ($canSeeSensitive) {
    $overview[] = [
        'href' => '/admin/solen.php?statut=integre', 'icon' => 'moon', 'tone' => 'purple',
        'n' => $solenIntegres,
        'label' => $plural($solenIntegres, 'Membre Solen intégré', 'Membres Solen intégrés'),
        'sub' => null,
    ];
}

// --- Raccourcis vers les pages où l'on crée du contenu ---
$shortcuts = [
    ['/admin/articles.php',      'file',     'Rédiger une actualité'],
    ['/admin/evenements.php',    'calendar', 'Créer un événement'],
    ['/admin/apprentissages.php', 'cap',     'Ajouter une ressource'],
    ['/admin/partenaires.php',   'partners', 'Ajouter un partenaire'],
    ['/admin/membres.php',       'users',    "Ajouter un membre d'équipe"],
];

$pageTitle = 'Tableau de bord';
require __DIR__ . '/includes/header.php';
?>

<!-- ============================================================
     BANDEAU D'ACCUEIL
     ============================================================ -->
<section class="dash-hero <?= $aTraiter > 0 ? '' : 'dash-hero--calm' ?>">
    <div class="dash-hero-text">
        <p class="dash-hero-date"><?= $e($dateFr) ?></p>
        <h2 class="dash-hero-title"><?= $e($salut) ?></h2>
        <p class="dash-hero-sub">
            <?php if ($aTraiter > 0): ?>
                <strong><?= (int) $aTraiter ?></strong>
                <?= $plural($aTraiter, 'élément attend', 'éléments attendent') ?> votre attention.
            <?php else: ?>
                Tout est à jour — rien n'attend votre attention pour le moment.
            <?php endif; ?>
        </p>
    </div>
    <div class="dash-hero-actions">
        <a href="/" target="_blank" rel="noopener" class="dash-hero-btn">
            <?= $svg('external', 16) ?> Voir le site public
        </a>
    </div>
</section>

<!-- ============================================================
     À TRAITER
     ============================================================ -->
<h2 class="dash-section-title">À traiter</h2>

<div class="dashboard-grid">
    <?php foreach ($todo as $c):
        $active = $c['n'] > 0;
    ?>
        <a href="<?= $e($c['href']) ?>"
           class="dashboard-card <?= $active ? 'dashboard-card--alert' : 'dashboard-card--calm' ?> <?= $c['urgent'] ? 'dashboard-card--urgent' : '' ?>">
            <div class="dashboard-card-icon dashboard-card-icon--<?= $e($c['tone']) ?>">
                <?= $svg($c['icon']) ?>
            </div>
            <div class="dashboard-card-content">
                <span class="dashboard-card-number"><?= (int) $c['n'] ?></span>
                <span class="dashboard-card-label"><?= $e($c['label']) ?></span>
                <?php if ($c['badge'] !== null): ?>
                    <span class="dashboard-card-badge"><?= $e($c['badge']) ?></span>
                <?php elseif ($active): ?>
                    <span class="dashboard-card-badge">À traiter</span>
                <?php else: ?>
                    <span class="dashboard-card-sub dashboard-card-sub--ok"><?= $svg('check', 12) ?> À jour</span>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<!-- ============================================================
     VUE D'ENSEMBLE
     ============================================================ -->
<h2 class="dash-section-title">Vue d'ensemble</h2>

<div class="dashboard-grid">
    <?php foreach ($overview as $c): ?>
        <a href="<?= $e($c['href']) ?>" class="dashboard-card">
            <div class="dashboard-card-icon dashboard-card-icon--<?= $e($c['tone']) ?>">
                <?= $svg($c['icon']) ?>
            </div>
            <div class="dashboard-card-content">
                <span class="dashboard-card-number"><?= (int) $c['n'] ?></span>
                <span class="dashboard-card-label"><?= $e($c['label']) ?></span>
                <?php if ($c['sub'] !== null): ?>
                    <span class="dashboard-card-sub"><?= $e($c['sub']) ?></span>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<!-- ============================================================
     ACTIVITÉ RÉCENTE + RACCOURCIS
     ============================================================ -->
<div class="dash-columns">

    <section class="admin-card">
        <div class="admin-card-head">
            <h2>Activité récente</h2>
        </div>

        <?php if (empty($activity)): ?>
            <p class="admin-empty">Aucune activité pour le moment.</p>
        <?php else: ?>
            <ul class="activity-list">
                <?php foreach ($activity as $a):
                    $icon = match ($a['type']) {
                        'contact'     => ['mail', 'teal'],
                        'adhesion'    => ['user-add', 'coral'],
                        'benevole'    => ['users', 'purple'],
                        'solen'       => ['moon', 'purple'],
                        'signalement' => ['alert', 'coral'],
                        default       => ['file', 'gray'],
                    };
                ?>
                    <li>
                        <a href="<?= $e($a['url']) ?>" class="activity-item <?= $a['nouveau'] ? 'is-new' : '' ?> <?= $a['urgent'] ? 'is-urgent' : '' ?>">
                            <span class="activity-icon dashboard-card-icon--<?= $e($icon[1]) ?>">
                                <?= $svg($icon[0], 18) ?>
                            </span>
                            <span class="activity-body">
                                <span class="activity-title"><?= $e($a['titre']) ?></span>
                                <span class="activity-detail"><?= $e($a['detail']) ?></span>
                            </span>
                            <span class="activity-meta">
                                <?php if ($a['nouveau']): ?>
                                    <span class="activity-new">Nouveau</span>
                                <?php endif; ?>
                                <time class="activity-time" datetime="<?= $e((string) $a['date']) ?>">
                                    <?= $e(dash_ago($a['age'], (string) $a['date'])) ?>
                                </time>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <section class="admin-card">
        <div class="admin-card-head">
            <h2>Raccourcis</h2>
        </div>

        <ul class="quick-links">
            <?php foreach ($shortcuts as [$href, $icon, $label]): ?>
                <li>
                    <a href="<?= $e($href) ?>" class="quick-link">
                        <span class="quick-link-icon"><?= $svg($icon, 18) ?></span>
                        <span class="quick-link-label"><?= $e($label) ?></span>
                        <span class="quick-link-plus"><?= $svg('plus', 16) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>

</div>

<!-- ============================================================
     DERNIÈRES ADHÉSIONS
     ============================================================ -->
<section class="admin-card">
    <div class="admin-card-head">
        <h2>Dernières adhésions</h2>
        <a href="/admin/adhesions.php" class="admin-link">Voir toutes les adhésions →</a>
    </div>

    <?php if (empty($recentAdhesions)): ?>
        <p class="admin-empty">Aucune demande d'adhésion pour le moment.</p>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Reçue le</th>
                        <th class="th-actions">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentAdhesions as $d): ?>
                        <tr>
                            <td>
                                <strong><?= $e($d['nom']) ?></strong>
                                <br>
                                <small class="admin-text-muted"><?= $e($d['email']) ?></small>
                            </td>
                            <td><?= $e($adhesionCats[$d['categorie']] ?? $d['categorie']) ?></td>
                            <td>
                                <span class="adhesion-badge adhesion-badge--<?= $e($d['statut']) ?>">
                                    <?= $e($adhesionLabels[$d['statut']] ?? $d['statut']) ?>
                                </span>
                            </td>
                            <td><?= $e(date('d/m/Y H:i', strtotime($d['created_at']))) ?></td>
                            <td class="td-actions">
                                <a href="/admin/adhesions.php?id=<?= (int) $d['id'] ?>" class="btn-action btn-action--edit">
                                    <?= $d['statut'] === 'en_attente' ? 'Examiner' : 'Voir' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
