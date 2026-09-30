<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/SolenAdhesion.php';

// Données très sensibles (téléphones, date de naissance) : réservé aux administrateurs
if (!AdminAuth::hasRole('super_admin', 'admin')) {
    http_response_code(403);
    $pageTitle = 'Accès réservé';
    require __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-error">Les demandes Solen sont réservées aux administrateurs. '
       . 'Contactez un administrateur si vous avez besoin d\'y accéder.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$model = new SolenAdhesion();

$statutLabels = [
    'nouveau'  => 'Nouvelle',
    'contacte' => 'Contactée',
    'integre'  => 'Intégrée',
    'clos'     => 'Clôturée',
];

// Étape logique suivante du parcours, proposée en bouton principal
$nextStep = [
    'nouveau'  => ['contacte', 'Marquer comme contactée'],
    'contacte' => ['integre',  'Marquer comme intégrée'],
    'integre'  => ['clos',     'Clôturer'],
    'clos'     => ['nouveau',  'Rouvrir'],
];

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$filtre = $_GET['statut'] ?? '';
if (!in_array($filtre, SolenAdhesion::STATUTS, true)) {
    $filtre = '';
}
$selectedId = (int) ($_GET['id'] ?? 0);

/** URL de la page en conservant le filtre courant. */
function solen_url(string $filtre, int $id = 0): string
{
    $params = [];
    if ($filtre !== '') $params['statut'] = $filtre;
    if ($id > 0)        $params['id']     = $id;
    return '/admin/solen.php' . ($params ? '?' . http_build_query($params) : '');
}

/**
 * Numéro utilisable dans un lien wa.me (chiffres uniquement, indicatif inclus).
 * Un mobile camerounais saisi sans indicatif (9 chiffres commençant par 6) reçoit le 237.
 */
function solen_wa_number(string $phone): string
{
    $digits = preg_replace('/\D/', '', $phone) ?? '';
    if (strlen($digits) === 9 && $digits[0] === '6') {
        $digits = '237' . $digits;
    }
    return $digits;
}

// --- Actions (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filtrePost = $_POST['filtre'] ?? '';
    if (!in_array($filtrePost, SolenAdhesion::STATUTS, true)) {
        $filtrePost = '';
    }

    $id     = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');

    if (hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? '')) && $id > 0) {
        if ($action === 'set_statut') {
            $model->setStatut($id, (string) ($_POST['statut'] ?? ''));
            header('Location: ' . solen_url($filtrePost, $id));
            exit;
        }
        if ($action === 'delete') {
            $model->delete($id);
            header('Location: ' . solen_url($filtrePost));
            exit;
        }
    }

    header('Location: ' . solen_url($filtrePost));
    exit;
}

$counts   = $model->countByStatut();
$total    = array_sum($counts);
$demandes = $model->getAll($filtre !== '' ? $filtre : null);

$selected = null;
foreach ($demandes as $d) {
    if ((int) $d['id'] === $selectedId) {
        $selected = $d;
        break;
    }
}

$tabs = ['' => ['Toutes', $total]];
foreach ($statutLabels as $value => $label) {
    // Pluriel des libellés (Nouvelle → Nouvelles, Contactée → Contactées…)
    $tabs[$value] = [$label . 's', $counts[$value]];
}

$e = static fn (string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');

$pageTitle = 'Communauté Solen';
require __DIR__ . '/includes/header.php';
?>

<p class="admin-form-hint" style="margin: 0 0 16px;">
    Données sensibles : téléphones et date de naissance de personnes en situation de vulnérabilité.
    Ne les copiez ni ne les partagez en dehors de l'accompagnement Solen.
</p>

<!-- Filtres par statut -->
<nav class="admin-filters" aria-label="Filtrer par statut">
    <?php foreach ($tabs as $value => [$label, $n]): ?>
        <a href="<?= $e(solen_url($value)) ?>"
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
            <h2>Demandes</h2>
            <span class="messages-count"><?= count($demandes) ?> demande<?= count($demandes) > 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($demandes)): ?>
            <div class="messages-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
                <p>Aucune demande Solen<?= $filtre !== '' ? ' avec ce statut' : ' pour le moment' ?>.</p>
            </div>
        <?php else: ?>

            <ul class="messages-list">
                <?php foreach ($demandes as $d):
                    $affiche = $d['pseudo'] !== null && $d['pseudo'] !== ''
                        ? $d['pseudo']
                        : trim($d['prenom'] . ' ' . $d['nom']);
                ?>
                    <li>
                        <a href="<?= $e(solen_url($filtre, (int) $d['id'])) ?>"
                           class="message-item <?= (int) $d['id'] === $selectedId ? 'is-active' : '' ?> <?= $d['statut'] === 'nouveau' ? 'is-unread' : '' ?>">

                            <div class="message-item-header">
                                <strong class="message-item-name"><?= $e($affiche) ?></strong>
                                <span class="adhesion-badge adhesion-badge--<?= $e($d['statut']) ?>">
                                    <?= $e($statutLabels[$d['statut']] ?? $d['statut']) ?>
                                </span>
                            </div>

                            <div class="message-item-subject">
                                <?= $e(SolenAdhesion::SITUATIONS[$d['situation']] ?? $d['situation']) ?>
                            </div>

                            <div class="message-item-date">
                                <?= $e($d['ville']) ?> · <?= $e(date('d/m/Y H:i', strtotime($d['created_at']))) ?>
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
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
                <h3>Sélectionnez une demande</h3>
                <p>Cliquez sur une demande dans la liste pour la consulter.</p>
            </div>

        <?php else:
            $sid    = (int) $selected['id'];
            $statut = $selected['statut'];
            $nom    = trim($selected['prenom'] . ' ' . $selected['nom']);
            $age    = (new DateTime($selected['date_naissance']))->diff(new DateTime())->y;
            [$nextStatut, $nextLabel] = $nextStep[$statut];
            $wa     = solen_wa_number($selected['telephone']);
        ?>

            <article class="message-detail">

                <header class="message-detail-header">
                    <div>
                        <h2><?= $e($nom) ?></h2>
                        <p class="message-detail-date">
                            Demande reçue le <?= $e(date('d/m/Y à H:i', strtotime($selected['created_at']))) ?>
                        </p>
                    </div>

                    <div class="message-detail-actions">
                        <form method="post" class="form-inline">
                            <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                            <input type="hidden" name="action" value="set_statut">
                            <input type="hidden" name="statut" value="<?= $e($nextStatut) ?>">
                            <input type="hidden" name="id" value="<?= $sid ?>">
                            <input type="hidden" name="filtre" value="<?= $e($filtre) ?>">
                            <button type="submit" class="btn-admin btn-admin--sm"><?= $e($nextLabel) ?></button>
                        </form>

                        <form method="post" class="form-inline" data-confirm="Supprimer définitivement cette demande Solen ?">
                            <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $sid ?>">
                            <input type="hidden" name="filtre" value="<?= $e($filtre) ?>">
                            <button type="submit" class="btn-action btn-action--delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6"/>
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Personne -->
                <div class="message-detail-sender">
                    <div class="message-sender-avatar">
                        <img src="/images/team/default-avatar.jpg" alt="">
                    </div>
                    <div class="message-sender-info">
                        <strong>
                            <?= $e($nom) ?>
                            <?php if ($selected['pseudo'] !== null && $selected['pseudo'] !== ''): ?>
                                <span class="admin-text-muted">· « <?= $e($selected['pseudo']) ?> »</span>
                            <?php endif; ?>
                        </strong>
                        <a href="mailto:<?= $e($selected['email']) ?>"><?= $e($selected['email']) ?></a>
                    </div>
                </div>

                <!-- Résumé -->
                <dl class="adhesion-meta">
                    <div>
                        <dt>Statut</dt>
                        <dd>
                            <span class="adhesion-badge adhesion-badge--<?= $e($statut) ?>">
                                <?= $e($statutLabels[$statut] ?? $statut) ?>
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt>Situation</dt>
                        <dd><?= $e(SolenAdhesion::SITUATIONS[$selected['situation']] ?? $selected['situation']) ?></dd>
                    </div>
                    <div>
                        <dt>Ville ou région</dt>
                        <dd><?= $e($selected['ville']) ?></dd>
                    </div>
                    <div>
                        <dt>Naissance</dt>
                        <dd><?= $e(date('d/m/Y', strtotime($selected['date_naissance']))) ?> (<?= (int) $age ?> ans)</dd>
                    </div>
                    <?php if ($selected['profession'] !== null && $selected['profession'] !== ''): ?>
                        <div>
                            <dt>Profession</dt>
                            <dd><?= $e($selected['profession']) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <!-- Joindre la personne / son proche -->
                <dl class="adhesion-meta">
                    <div>
                        <dt>Téléphone</dt>
                        <dd><a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $selected['telephone']) ?? '') ?>"><?= $e($selected['telephone']) ?></a></dd>
                    </div>
                    <div>
                        <dt>Proche de confiance</dt>
                        <dd>
                            <a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $selected['telephone_proche']) ?? '') ?>"><?= $e($selected['telephone_proche']) ?></a>
                            <br><small class="admin-text-muted">À joindre uniquement en cas de nécessité.</small>
                        </dd>
                    </div>
                    <div>
                        <dt>Consentements</dt>
                        <dd>
                            <small>
                                Confidentialité <?= (int) $selected['accepte_confidentialite'] ? '✓' : '✗' ?> ·
                                Charte <?= (int) $selected['accepte_charte'] ? '✓' : '✗' ?> ·
                                WhatsApp <?= (int) $selected['accepte_whatsapp'] ? '✓' : '✗' ?> ·
                                Newsletter <?= (int) $selected['accepte_newsletter'] ? '✓' : '✗' ?>
                            </small>
                        </dd>
                    </div>
                </dl>

                <footer class="message-detail-footer" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <?php if ($wa !== '' && (int) $selected['accepte_whatsapp'] === 1): ?>
                        <a href="https://wa.me/<?= $e($wa) ?>" target="_blank" rel="noopener noreferrer" class="btn-admin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            Écrire sur WhatsApp
                        </a>
                    <?php endif; ?>
                    <a href="mailto:<?= $e($selected['email']) ?>?subject=<?= $e(rawurlencode('Bienvenue dans la communauté Solen')) ?>"
                       class="btn-admin btn-admin--ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        Répondre par email
                    </a>
                </footer>

            </article>

        <?php endif; ?>

    </div>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
