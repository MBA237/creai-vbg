<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Don.php';
require_once __DIR__ . '/../../src/paiement.php';
require_once __DIR__ . '/../../src/mails.php';

// Coordonnées de donateurs : réservé aux administrateurs
if (!AdminAuth::hasRole('super_admin', 'admin')) {
    http_response_code(403);
    $pageTitle = 'Accès réservé';
    require __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-error">Les dons sont réservés aux administrateurs.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$model = new Don();

$statutLabels = [
    'en_attente' => 'En attente',
    'recu'       => 'Reçu',
    'annule'     => 'Annulé',
];

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$filtre = $_GET['statut'] ?? '';
if (!in_array($filtre, Don::STATUTS, true)) {
    $filtre = '';
}
$selectedId = (int) ($_GET['id'] ?? 0);

/** URL de la page en conservant le filtre courant. */
function dons_url(string $filtre, int $id = 0): string
{
    $params = [];
    if ($filtre !== '') $params['statut'] = $filtre;
    if ($id > 0)        $params['id']     = $id;
    return '/admin/dons.php' . ($params ? '?' . http_build_query($params) : '');
}

// --- Actions (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filtrePost = $_POST['filtre'] ?? '';
    if (!in_array($filtrePost, Don::STATUTS, true)) {
        $filtrePost = '';
    }

    $id     = (int) ($_POST['id'] ?? 0);
    $action = (string) ($_POST['action'] ?? '');

    if (hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? '')) && $id > 0) {
        if ($action === 'set_statut') {
            $nouveau = (string) ($_POST['statut'] ?? '');
            $avant   = $model->find($id);
            if ($model->setStatut($id, $nouveau) && $avant && $nouveau === 'recu' && $avant['statut'] !== 'recu') {
                mail_don_recu($avant);
            }
            header('Location: ' . dons_url($filtrePost, $id));
            exit;
        }
        if ($action === 'delete') {
            $model->delete($id);
            header('Location: ' . dons_url($filtrePost));
            exit;
        }
    }

    header('Location: ' . dons_url($filtrePost));
    exit;
}

$counts = $model->countByStatut();
$total  = array_sum($counts);
$dons   = $model->getAll($filtre !== '' ? $filtre : null);

$selected = null;
foreach ($dons as $d) {
    if ((int) $d['id'] === $selectedId) {
        $selected = $d;
        break;
    }
}

$tabs = ['' => ['Tous', $total]];
foreach ($statutLabels as $value => $label) {
    $tabs[$value] = [$label, $counts[$value]];
}

$e     = static fn (string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$euros = static fn (mixed $v): string => rtrim(rtrim(number_format((float) $v, 2, ',', ' '), '0'), ',') . ' €';

$pageTitle = 'Dons';
require __DIR__ . '/includes/header.php';
?>

<p class="admin-form-hint" style="margin: 0 0 16px;">
    Le site n'encaisse pas lui-même : passez un don à « Reçu » une fois le règlement constaté
    (carte, PayPal, Mobile Money ou virement). Total reçu à ce jour :
    <strong><?= $e($euros($model->totalRecu())) ?></strong>.
</p>

<!-- Filtres par statut -->
<nav class="admin-filters" aria-label="Filtrer par statut">
    <?php foreach ($tabs as $value => [$label, $n]): ?>
        <a href="<?= $e(dons_url($value)) ?>"
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
            <h2>Dons</h2>
            <span class="messages-count"><?= count($dons) ?> don<?= count($dons) > 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($dons)): ?>
            <div class="messages-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <p>Aucun don<?= $filtre !== '' ? ' avec ce statut' : ' pour le moment' ?>.</p>
            </div>
        <?php else: ?>

            <ul class="messages-list">
                <?php foreach ($dons as $d): ?>
                    <li>
                        <a href="<?= $e(dons_url($filtre, (int) $d['id'])) ?>"
                           class="message-item <?= (int) $d['id'] === $selectedId ? 'is-active' : '' ?> <?= $d['statut'] === 'en_attente' ? 'is-unread' : '' ?>">

                            <div class="message-item-header">
                                <strong class="message-item-name"><?= $e(trim($d['prenom'] . ' ' . $d['nom'])) ?></strong>
                                <span class="adhesion-badge adhesion-badge--<?= $e($d['statut']) ?>">
                                    <?= $e($statutLabels[$d['statut']] ?? $d['statut']) ?>
                                </span>
                            </div>

                            <div class="message-item-subject">
                                <?= $e($euros($d['montant'])) ?><?= $d['mode'] === 'mensuel' ? ' / mois' : '' ?>
                                · <?= $e(PAIEMENT_MOYENS[$d['moyen']] ?? $d['moyen']) ?>
                            </div>

                            <div class="message-item-date">
                                <?= $e($d['reference']) ?> · <?= $e(date('d/m/Y H:i', strtotime($d['created_at']))) ?>
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
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h3>Sélectionnez un don</h3>
                <p>Cliquez sur un don dans la liste pour le consulter.</p>
            </div>

        <?php else:
            $sid    = (int) $selected['id'];
            $statut = $selected['statut'];
            $nom    = trim($selected['prenom'] . ' ' . $selected['nom']);
        ?>

            <article class="message-detail">

                <header class="message-detail-header">
                    <div>
                        <h2><?= $e($euros($selected['montant'])) ?><?= $selected['mode'] === 'mensuel' ? ' par mois' : '' ?></h2>
                        <p class="message-detail-date">
                            <?= $e($selected['reference']) ?> · déclaré le
                            <?= $e(date('d/m/Y à H:i', strtotime($selected['created_at']))) ?>
                        </p>
                    </div>

                    <div class="message-detail-actions">
                        <?php foreach (['recu' => 'Marquer comme reçu', 'en_attente' => 'Remettre en attente', 'annule' => 'Annuler'] as $target => $label):
                            if ($target === $statut) continue; ?>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                                <input type="hidden" name="action" value="set_statut">
                                <input type="hidden" name="statut" value="<?= $e($target) ?>">
                                <input type="hidden" name="id" value="<?= $sid ?>">
                                <input type="hidden" name="filtre" value="<?= $e($filtre) ?>">
                                <button type="submit" class="btn-admin btn-admin--sm <?= $target === 'recu' ? '' : 'btn-admin--ghost' ?>"><?= $e($label) ?></button>
                            </form>
                        <?php endforeach; ?>

                        <form method="post" class="form-inline" data-confirm="Supprimer définitivement ce don ?">
                            <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $sid ?>">
                            <input type="hidden" name="filtre" value="<?= $e($filtre) ?>">
                            <button type="submit" class="btn-action btn-action--delete">Supprimer</button>
                        </form>
                    </div>
                </header>

                <div class="message-detail-sender">
                    <div class="message-sender-info">
                        <strong>
                            <?= $e($nom) ?>
                            <?php if ((int) $selected['organisation'] === 1): ?>
                                <span class="admin-text-muted">· au nom d'une organisation</span>
                            <?php endif; ?>
                        </strong>
                        <a href="mailto:<?= $e($selected['email']) ?>"><?= $e($selected['email']) ?></a>
                    </div>
                </div>

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
                        <dt>Type</dt>
                        <dd><?= $selected['mode'] === 'mensuel' ? 'Don mensuel' : 'Don unique' ?></dd>
                    </div>
                    <div>
                        <dt>Moyen de paiement</dt>
                        <dd>
                            <?= $e(PAIEMENT_MOYENS[$selected['moyen']] ?? $selected['moyen']) ?>
                            <?php if ($selected['moyen'] === 'mobile'): ?>
                                <br><small class="admin-text-muted">
                                    <?= $e(PAIEMENT_OPERATEURS[$selected['mobile_operateur'] ?? ''] ?? '') ?>
                                    <?= $e((string) $selected['mobile_numero']) ?>
                                </small>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div>
                        <dt>Téléphone</dt>
                        <dd><a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $selected['telephone']) ?? '') ?>"><?= $e($selected['telephone']) ?></a></dd>
                    </div>
                    <?php if ($selected['adresse'] !== null && $selected['adresse'] !== ''): ?>
                        <div>
                            <dt>Adresse</dt>
                            <dd><?= $e($selected['adresse']) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <footer class="message-detail-footer">
                    <a href="mailto:<?= $e($selected['email']) ?>?subject=<?= $e(rawurlencode('Votre don au CREAI-VBG — ' . $selected['reference'])) ?>"
                       class="btn-admin btn-admin--ghost">Répondre par email</a>
                </footer>

            </article>

        <?php endif; ?>

    </div>

</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
