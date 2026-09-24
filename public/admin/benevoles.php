<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Benevole.php';

$benevoleModel = new Benevole();

// --- Actions (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        $action = $_POST['action'] ?? '';
        $id     = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            if ($action === 'mark_read') {
                $benevoleModel->markAsRead($id);
                header('Location: /admin/benevoles.php?id=' . $id);
                exit;
            }

            if ($action === 'delete') {
                $benevoleModel->delete($id);
                header('Location: /admin/benevoles.php');
                exit;
            }
        }
    }

    header('Location: /admin/benevoles.php');
    exit;
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$candidatures = $benevoleModel->getAll();
$selectedId   = (int) ($_GET['id'] ?? 0);
$selected     = null;

if ($selectedId > 0) {
    foreach ($candidatures as $c) {
        if ((int) $c['id'] === $selectedId) {
            $selected = $c;
            break;
        }
    }
}

$pageTitle = 'Candidatures bénévoles';
require __DIR__ . '/includes/header.php';
?>

<!-- ============================================================
     LAYOUT 2 COLONNES : liste + détail
     ============================================================ -->
<div class="messages-layout">

    <!-- Colonne liste -->
    <div class="messages-list-col">

        <div class="messages-list-header">
            <h2>Candidatures</h2>
            <span class="messages-count"><?= count($candidatures) ?> candidature<?= count($candidatures) > 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($candidatures)): ?>
            <div class="messages-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <p>Aucune candidature bénévole pour le moment.</p>
            </div>
        <?php else: ?>

            <ul class="messages-list">
                <?php foreach ($candidatures as $c): ?>
                    <li>
                        <a href="/admin/benevoles.php?id=<?= (int) $c['id'] ?>"
                           class="message-item <?= (int) $c['id'] === $selectedId ? 'is-active' : '' ?> <?= (int) $c['lu'] === 0 ? 'is-unread' : '' ?>">

                            <div class="message-item-header">
                                <strong class="message-item-name">
                                    <?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?>
                                </strong>
                                <?php if ((int) $c['lu'] === 0): ?>
                                    <span class="message-item-dot"></span>
                                <?php endif; ?>
                            </div>

                            <div class="message-item-subject">
                                <?= htmlspecialchars($c['ville']) ?>
                            </div>

                            <div class="message-item-date">
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($c['created_at']))) ?>
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
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <h3>Sélectionnez une candidature</h3>
                <p>Cliquez sur une candidature dans la liste pour l'examiner.</p>
            </div>

        <?php else: ?>

            <article class="message-detail">

                <header class="message-detail-header">
                    <div>
                        <h2><?= htmlspecialchars($selected['prenom'] . ' ' . $selected['nom']) ?></h2>
                        <p class="message-detail-date">
                            Candidature reçue le <?= htmlspecialchars(date('d/m/Y à H:i', strtotime($selected['created_at']))) ?>
                        </p>
                    </div>

                    <div class="message-detail-actions">
                        <?php if ((int) $selected['lu'] === 0): ?>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                <input type="hidden" name="action" value="mark_read">
                                <input type="hidden" name="id" value="<?= (int) $selected['id'] ?>">
                                <button type="submit" class="btn-admin btn-admin--sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    Marquer comme lu
                                </button>
                            </form>
                        <?php endif; ?>

                        <form method="post" class="form-inline" data-confirm="Supprimer définitivement cette candidature ?">
                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $selected['id'] ?>">
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

                <!-- Candidat -->
                <div class="message-detail-sender">
                    <div class="message-sender-avatar">
                        <img src="/images/team/default-avatar.jpg" alt="">
                    </div>
                    <div class="message-sender-info">
                        <strong><?= htmlspecialchars($selected['prenom'] . ' ' . $selected['nom']) ?></strong>
                        <a href="mailto:<?= htmlspecialchars($selected['email']) ?>">
                            <?= htmlspecialchars($selected['email']) ?>
                        </a>
                    </div>
                </div>

                <!-- Résumé -->
                <dl class="adhesion-meta">
                    <div>
                        <dt>Téléphone</dt>
                        <dd><a href="tel:<?= htmlspecialchars(preg_replace('/[^\d+]/', '', $selected['telephone']) ?? '') ?>"><?= htmlspecialchars($selected['telephone']) ?></a></dd>
                    </div>
                    <div>
                        <dt>Date de naissance</dt>
                        <dd><?= htmlspecialchars(date('d/m/Y', strtotime($selected['date_naissance']))) ?></dd>
                    </div>
                    <div>
                        <dt>Ville</dt>
                        <dd><?= htmlspecialchars($selected['ville']) ?></dd>
                    </div>
                    <div>
                        <dt>Canal préféré</dt>
                        <dd><?= htmlspecialchars(Benevole::CANAUX[$selected['canal']] ?? $selected['canal']) ?></dd>
                    </div>
                    <?php if (!empty($selected['connu_par'])): ?>
                        <div>
                            <dt>Comment connu(e)</dt>
                            <dd><?= htmlspecialchars($selected['connu_par']) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>

                <!-- Motivations -->
                <?php if (trim((string) $selected['motivations']) !== ''): ?>
                    <div class="message-detail-section">
                        <h3 class="message-detail-subtitle">Motivations</h3>
                        <p><?= nl2br(htmlspecialchars($selected['motivations'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Expérience -->
                <?php if (trim((string) $selected['experience']) !== ''): ?>
                    <div class="message-detail-section">
                        <h3 class="message-detail-subtitle">Expérience bénévole</h3>
                        <p><?= nl2br(htmlspecialchars($selected['experience'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Notes libres -->
                <?php if (trim((string) $selected['notes']) !== ''): ?>
                    <div class="message-detail-section">
                        <h3 class="message-detail-subtitle">Autre chose à savoir</h3>
                        <p><?= nl2br(htmlspecialchars($selected['notes'])) ?></p>
                    </div>
                <?php endif; ?>

                <footer class="message-detail-footer">
                    <a href="mailto:<?= htmlspecialchars($selected['email']) ?>?subject=<?= htmlspecialchars(rawurlencode('Votre candidature bénévole au CREAI-VBG')) ?>"
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
