<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Membership.php';

$membershipModel = new Membership();

$statutLabels = [
    'en_attente' => 'En attente',
    'agree'      => 'Agréée',
    'refuse'     => 'Refusée',
];

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Filtre de la liste (?statut=en_attente|agree|refuse), vide = toutes
$filtre = $_GET['statut'] ?? '';
if (!in_array($filtre, Membership::STATUTS, true)) {
    $filtre = '';
}

$selectedId = (int) ($_GET['id'] ?? 0);

/** URL de la page en conservant le filtre courant. */
function adhesions_url(string $filtre, int $id = 0): string
{
    $params = [];
    if ($filtre !== '') $params['statut'] = $filtre;
    if ($id > 0)        $params['id']     = $id;
    return '/admin/adhesions.php' . ($params ? '?' . http_build_query($params) : '');
}

// --- Actions (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filtrePost = $_POST['filtre'] ?? '';
    if (!in_array($filtrePost, Membership::STATUTS, true)) {
        $filtrePost = '';
    }

    $id     = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if (hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? '')) && $id > 0) {

        if ($action === 'set_statut') {
            $membershipModel->setStatut($id, (string) ($_POST['statut'] ?? ''));
            header('Location: ' . adhesions_url($filtrePost, $id));
            exit;
        }

        if ($action === 'delete') {
            $membershipModel->delete($id);
            header('Location: ' . adhesions_url($filtrePost));
            exit;
        }
    }

    header('Location: ' . adhesions_url($filtrePost));
    exit;
}

$counts   = $membershipModel->countByStatut();
$total    = array_sum($counts);
$demandes = $membershipModel->getAll($filtre !== '' ? $filtre : null);

$selected = null;
foreach ($demandes as $d) {
    if ((int) $d['id'] === $selectedId) {
        $selected = $d;
        break;
    }
}

$tabs = [
    ''           => ['Toutes', $total],
    'en_attente' => ['En attente', $counts['en_attente']],
    'agree'      => ['Agréées', $counts['agree']],
    'refuse'     => ['Refusées', $counts['refuse']],
];

$pageTitle = "Demandes d'adhésion";
require __DIR__ . '/includes/header.php';
?>

<!-- Filtres par statut -->
<nav class="admin-filters" aria-label="Filtrer par statut">
    <?php foreach ($tabs as $value => [$label, $n]): ?>
        <a href="<?= htmlspecialchars(adhesions_url($value)) ?>"
           class="admin-filter <?= $filtre === $value ? 'is-active' : '' ?>">
            <?= htmlspecialchars($label) ?>
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
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                <p>Aucune demande d'adhésion<?= $filtre !== '' ? ' avec ce statut' : ' pour le moment' ?>.</p>
            </div>
        <?php else: ?>

            <ul class="messages-list">
                <?php foreach ($demandes as $d): ?>
                    <li>
                        <a href="<?= htmlspecialchars(adhesions_url($filtre, (int) $d['id'])) ?>"
                           class="message-item <?= (int) $d['id'] === $selectedId ? 'is-active' : '' ?> <?= $d['statut'] === 'en_attente' ? 'is-unread' : '' ?>">

                            <div class="message-item-header">
                                <strong class="message-item-name"><?= htmlspecialchars($d['nom']) ?></strong>
                                <span class="adhesion-badge adhesion-badge--<?= htmlspecialchars($d['statut']) ?>">
                                    <?= htmlspecialchars($statutLabels[$d['statut']] ?? $d['statut']) ?>
                                </span>
                            </div>

                            <div class="message-item-subject">
                                <?= htmlspecialchars(Membership::CATEGORIES[$d['categorie']] ?? $d['categorie']) ?>
                            </div>

                            <div class="message-item-date">
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($d['created_at']))) ?>
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
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                <h3>Sélectionnez une demande</h3>
                <p>Cliquez sur une demande dans la liste pour l'examiner.</p>
            </div>

        <?php else:
            $sid = (int) $selected['id'];
            $statut = $selected['statut'];
        ?>

            <article class="message-detail">

                <header class="message-detail-header">
                    <div>
                        <h2><?= htmlspecialchars($selected['nom']) ?></h2>
                        <p class="message-detail-date">
                            Demande reçue le <?= htmlspecialchars(date('d/m/Y à H:i', strtotime($selected['created_at']))) ?>
                        </p>
                    </div>

                    <div class="message-detail-actions">
                        <?php if ($statut === 'en_attente'): ?>

                            <form method="post" class="form-inline">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                <input type="hidden" name="action" value="set_statut">
                                <input type="hidden" name="statut" value="agree">
                                <input type="hidden" name="id" value="<?= $sid ?>">
                                <input type="hidden" name="filtre" value="<?= htmlspecialchars($filtre) ?>">
                                <button type="submit" class="btn-admin btn-admin--sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    Agréer
                                </button>
                            </form>

                            <form method="post" class="form-inline">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                <input type="hidden" name="action" value="set_statut">
                                <input type="hidden" name="statut" value="refuse">
                                <input type="hidden" name="id" value="<?= $sid ?>">
                                <input type="hidden" name="filtre" value="<?= htmlspecialchars($filtre) ?>">
                                <button type="submit" class="btn-admin btn-admin--ghost btn-admin--sm">Refuser</button>
                            </form>

                        <?php else: ?>

                            <form method="post" class="form-inline">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                <input type="hidden" name="action" value="set_statut">
                                <input type="hidden" name="statut" value="en_attente">
                                <input type="hidden" name="id" value="<?= $sid ?>">
                                <input type="hidden" name="filtre" value="<?= htmlspecialchars($filtre) ?>">
                                <button type="submit" class="btn-admin btn-admin--ghost btn-admin--sm">Remettre en attente</button>
                            </form>

                        <?php endif; ?>

                        <form method="post" class="form-inline" data-confirm="Supprimer définitivement cette demande d'adhésion ?">
                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $sid ?>">
                            <input type="hidden" name="filtre" value="<?= htmlspecialchars($filtre) ?>">
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

                <!-- Demandeur -->
                <div class="message-detail-sender">
                    <div class="message-sender-avatar">
                        <img src="/images/team/default-avatar.jpg" alt="">
                    </div>
                    <div class="message-sender-info">
                        <strong><?= htmlspecialchars($selected['nom']) ?></strong>
                        <a href="mailto:<?= htmlspecialchars($selected['email']) ?>">
                            <?= htmlspecialchars($selected['email']) ?>
                        </a>
                    </div>
                </div>

                <!-- Résumé de la demande -->
                <dl class="adhesion-meta">
                    <div>
                        <dt>Catégorie demandée</dt>
                        <dd><?= htmlspecialchars(Membership::CATEGORIES[$selected['categorie']] ?? $selected['categorie']) ?></dd>
                    </div>
                    <div>
                        <dt>Statut</dt>
                        <dd>
                            <span class="adhesion-badge adhesion-badge--<?= htmlspecialchars($statut) ?>">
                                <?= htmlspecialchars($statutLabels[$statut] ?? $statut) ?>
                            </span>
                        </dd>
                    </div>
                    <?php if (!empty($selected['traite_at'])): ?>
                        <div>
                            <dt>Décision le</dt>
                            <dd><?= htmlspecialchars(date('d/m/Y à H:i', strtotime($selected['traite_at']))) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <!-- Message du demandeur -->
                <div class="message-detail-body">
                    <?php if (trim((string) $selected['message']) === ''): ?>
                        <em class="adhesion-no-message">Aucun message joint à la demande.</em>
                    <?php else: ?>
                        <?= nl2br(htmlspecialchars($selected['message'])) ?>
                    <?php endif; ?>
                </div>

                <footer class="message-detail-footer">
                    <a href="mailto:<?= htmlspecialchars($selected['email']) ?>?subject=<?= htmlspecialchars(rawurlencode('Votre demande d\'adhésion au CREAI-VBG')) ?>"
                       class="btn-admin">
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
