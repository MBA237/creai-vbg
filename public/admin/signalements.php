<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Signalement.php';

// Données très sensibles : réservé aux administrateurs (pas au rôle « editeur »)
if (!AdminAuth::hasRole('super_admin', 'admin')) {
    http_response_code(403);
    $pageTitle = 'Accès réservé';
    require __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-error">Les signalements sont réservés aux administrateurs. '
       . 'Contactez un administrateur si vous avez besoin d\'y accéder.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$model = new Signalement();

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$filtre = $_GET['statut'] ?? '';
if (!isset(Signalement::STATUTS[$filtre])) {
    $filtre = '';
}
$selectedId = (int) ($_GET['id'] ?? 0);

/** URL de la page en conservant le filtre courant. */
function signalements_url(string $filtre, int $id = 0): string
{
    $params = [];
    if ($filtre !== '') $params['statut'] = $filtre;
    if ($id > 0)        $params['id']     = $id;
    return '/admin/signalements.php' . ($params ? '?' . http_build_query($params) : '');
}

// --- Actions (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filtrePost = $_POST['filtre'] ?? '';
    if (!isset(Signalement::STATUTS[$filtrePost])) {
        $filtrePost = '';
    }

    $id     = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');

    if (hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? '')) && $id > 0) {
        if ($action === 'set_statut') {
            $model->setStatut($id, (string) ($_POST['statut'] ?? ''));
            header('Location: ' . signalements_url($filtrePost, $id));
            exit;
        }
        if ($action === 'delete') {
            $model->delete($id);
            header('Location: ' . signalements_url($filtrePost));
            exit;
        }
    }

    header('Location: ' . signalements_url($filtrePost));
    exit;
}

$counts      = $model->counts();
$total       = $counts['nouveau'] + $counts['en_cours'] + $counts['traite'];
$signalements = $model->getAll($filtre !== '' ? $filtre : null);

$selected = null;
foreach ($signalements as $s) {
    if ((int) $s['id'] === $selectedId) {
        $selected = $s;
        break;
    }
}

$tabs = [
    ''         => ['Tous', $total],
    'nouveau'  => ['Nouveaux', $counts['nouveau']],
    'en_cours' => ['En cours', $counts['en_cours']],
    'traite'   => ['Traités', $counts['traite']],
];

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

$pageTitle = 'Signalements';
require __DIR__ . '/includes/header.php';
?>

<?php if ($counts['danger_nouveau'] > 0): ?>
    <div class="alert alert-error" role="alert">
        <strong><?= (int) $counts['danger_nouveau'] ?> nouveau<?= $counts['danger_nouveau'] > 1 ? 'x' : '' ?> signalement<?= $counts['danger_nouveau'] > 1 ? 's' : '' ?> avec « danger immédiat »</strong>
        à traiter en priorité.
    </div>
<?php endif; ?>

<!-- Filtres par statut -->
<nav class="admin-filters" aria-label="Filtrer par statut">
    <?php foreach ($tabs as $value => [$label, $n]): ?>
        <a href="<?= $e(signalements_url($value)) ?>"
           class="admin-filter <?= $filtre === $value ? 'is-active' : '' ?>">
            <?= $e($label) ?>
            <span class="admin-filter-count"><?= (int) $n ?></span>
        </a>
    <?php endforeach; ?>
</nav>

<div class="messages-layout">

    <!-- Colonne liste -->
    <div class="messages-list-col">

        <div class="messages-list-header">
            <h2>Signalements</h2>
            <span class="messages-count"><?= count($signalements) ?></span>
        </div>

        <?php if (empty($signalements)): ?>
            <div class="messages-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <p>Aucun signalement<?= $filtre !== '' ? ' avec ce statut' : ' pour le moment' ?>.</p>
            </div>
        <?php else: ?>
            <ul class="messages-list">
                <?php foreach ($signalements as $s): ?>
                    <li>
                        <a href="<?= $e(signalements_url($filtre, (int) $s['id'])) ?>"
                           class="message-item <?= (int) $s['id'] === $selectedId ? 'is-active' : '' ?> <?= $s['statut'] === 'nouveau' ? 'is-unread' : '' ?>">

                            <div class="message-item-header">
                                <strong class="message-item-name"><?= $e($s['reference']) ?></strong>
                                <span class="sig-flags">
                                    <?php if ((int) $s['danger_immediat'] === 1): ?><span class="sig-flag sig-flag--danger">Danger</span><?php endif; ?>
                                    <?php if ($s['mineur'] === 'oui'): ?><span class="sig-flag sig-flag--mineur">Mineur</span><?php endif; ?>
                                </span>
                            </div>

                            <div class="message-item-subject">
                                <?= $e(Signalement::POUR_QUI[$s['pour_qui']] ?? $s['pour_qui']) ?>
                            </div>

                            <div class="message-item-preview">
                                <?= $e(mb_substr($s['description'], 0, 80)) ?><?= mb_strlen($s['description']) > 80 ? '…' : '' ?>
                            </div>

                            <div class="message-item-date">
                                <span class="adhesion-badge sig-statut sig-statut--<?= $e($s['statut']) ?>"><?= $e(Signalement::STATUTS[$s['statut']]) ?></span>
                                <?= $e(date('d/m/Y H:i', strtotime($s['created_at']))) ?>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- Colonne détail -->
    <div class="messages-detail-col">

        <?php if (!$selected): ?>

            <div class="messages-detail-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <h3>Sélectionnez un signalement</h3>
                <p>Cliquez sur un signalement dans la liste pour le lire.</p>
            </div>

        <?php else:
            $sid    = (int) $selected['id'];
            $statut = $selected['statut'];
            $types  = Signalement::parseTypes($selected['types']);
            $canal  = $selected['contact_canal'];
            $moyen  = (string) $selected['contact_moyen'];
        ?>

            <article class="message-detail">

                <header class="message-detail-header">
                    <div>
                        <h2><?= $e($selected['reference']) ?></h2>
                        <p class="message-detail-date">
                            Reçu le <?= $e(date('d/m/Y à H:i', strtotime($selected['created_at']))) ?>
                        </p>
                    </div>

                    <div class="message-detail-actions">
                        <?php
                        // Bouton de changement de statut : [libellé, statut cible, style]
                        $boutons = match ($statut) {
                            'nouveau'  => [['Prendre en charge', 'en_cours', 'primary'], ['Marquer comme traité', 'traite', 'ghost']],
                            'en_cours' => [['Marquer comme traité', 'traite', 'primary'], ['Remettre en nouveau', 'nouveau', 'ghost']],
                            default    => [['Rouvrir', 'en_cours', 'ghost']],
                        };
                        foreach ($boutons as [$libelle, $cible, $style]): ?>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                                <input type="hidden" name="action" value="set_statut">
                                <input type="hidden" name="statut" value="<?= $e($cible) ?>">
                                <input type="hidden" name="id" value="<?= $sid ?>">
                                <input type="hidden" name="filtre" value="<?= $e($filtre) ?>">
                                <button type="submit" class="btn-admin <?= $style === 'ghost' ? 'btn-admin--ghost' : '' ?> btn-admin--sm"><?= $e($libelle) ?></button>
                            </form>
                        <?php endforeach; ?>

                        <form method="post" class="form-inline" data-confirm="Supprimer définitivement ce signalement ? Cette action est irréversible.">
                            <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $sid ?>">
                            <input type="hidden" name="filtre" value="<?= $e($filtre) ?>">
                            <button type="submit" class="btn-action btn-action--delete">Supprimer</button>
                        </form>
                    </div>
                </header>

                <?php if ((int) $selected['danger_immediat'] === 1 || $selected['mineur'] === 'oui'): ?>
                    <div class="sig-alerts">
                        <?php if ((int) $selected['danger_immediat'] === 1): ?>
                            <div class="sig-alert sig-alert--danger">⚠️ <strong>Danger immédiat</strong> signalé : à traiter en priorité.</div>
                        <?php endif; ?>
                        <?php if ($selected['mineur'] === 'oui'): ?>
                            <div class="sig-alert sig-alert--mineur">👶 <strong>Une personne mineure est concernée</strong> : protection de l'enfance.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <dl class="adhesion-meta">
                    <div>
                        <dt>Concerné(e)</dt>
                        <dd><?= $e(Signalement::POUR_QUI[$selected['pour_qui']] ?? $selected['pour_qui']) ?></dd>
                    </div>
                    <div>
                        <dt>Mineur</dt>
                        <dd><?= $e(['oui' => 'Oui', 'non' => 'Non', 'inconnu' => 'Inconnu'][$selected['mineur']] ?? '') ?></dd>
                    </div>
                    <div>
                        <dt>Statut</dt>
                        <dd><span class="adhesion-badge sig-statut sig-statut--<?= $e($statut) ?>"><?= $e(Signalement::STATUTS[$statut]) ?></span></dd>
                    </div>
                    <div class="sig-meta-wide">
                        <dt>Type(s) de violence</dt>
                        <dd><?= $e(implode(' · ', array_map(fn ($t) => Signalement::TYPES[$t], $types))) ?></dd>
                    </div>
                    <?php if (!empty($selected['lieu'])): ?>
                        <div><dt>Lieu</dt><dd><?= $e($selected['lieu']) ?></dd></div>
                    <?php endif; ?>
                    <?php if (!empty($selected['date_faits'])): ?>
                        <div><dt>Quand</dt><dd><?= $e($selected['date_faits']) ?></dd></div>
                    <?php endif; ?>
                </dl>

                <div class="message-detail-body"><?= nl2br($e($selected['description'])) ?></div>

                <!-- Contact -->
                <div class="sig-contact">
                    <?php if ($moyen === ''): ?>
                        <p class="sig-contact-anon">🔒 <strong>Signalement anonyme</strong> : aucun moyen de contact n'a été laissé.</p>
                    <?php else: ?>
                        <h3>Contact laissé par la personne</h3>
                        <dl class="adhesion-meta sig-contact-meta">
                            <?php if (!empty($selected['contact_nom'])): ?>
                                <div><dt>Nom / pseudo</dt><dd><?= $e($selected['contact_nom']) ?></dd></div>
                            <?php endif; ?>
                            <div><dt>Moyen</dt><dd><?= $e(Signalement::CANAUX[$canal] ?? '') ?></dd></div>
                            <div>
                                <dt>Coordonnées</dt>
                                <dd>
                                    <?php if ($canal === 'email'): ?>
                                        <a href="mailto:<?= $e($moyen) ?>"><?= $e($moyen) ?></a>
                                    <?php elseif ($canal === 'whatsapp'): ?>
                                        <a href="https://wa.me/<?= $e(preg_replace('/\D/', '', $moyen) ?? '') ?>" target="_blank" rel="noopener noreferrer"><?= $e($moyen) ?></a>
                                    <?php else: ?>
                                        <a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $moyen) ?? '') ?>"><?= $e($moyen) ?></a>
                                    <?php endif; ?>
                                </dd>
                            </div>
                        </dl>
                        <?php if ((int) $selected['contact_sur'] === 1): ?>
                            <p class="sig-contact-ok">✅ La personne a indiqué que ce moyen est <strong>sûr</strong>.</p>
                        <?php else: ?>
                            <p class="sig-contact-warn">⚠️ La personne <strong>n'a pas confirmé</strong> que ce moyen est sûr : ne l'utilisez pas sans précaution (elle pourrait être surveillée).</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

            </article>

        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
