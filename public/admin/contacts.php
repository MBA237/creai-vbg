<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Contact.php';

$contactModel = new Contact();

// --- Marquer comme lu ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        // Token invalide, on ignore
    } else {
        $action = $_POST['action'] ?? '';
        $id     = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $conn = (new Database())->connect();

            if ($action === 'mark_read') {
                $stmt = $conn->prepare("UPDATE contacts SET lu = 1 WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
                header('Location: /admin/contacts.php?msg=' . $id);
                exit;
            }

            if ($action === 'mark_unread') {
                $stmt = $conn->prepare("UPDATE contacts SET lu = 0 WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
                header('Location: /admin/contacts.php');
                exit;
            }

            if ($action === 'delete') {
                $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $stmt->close();
                header('Location: /admin/contacts.php');
                exit;
            }
        }
    }
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$messages = $contactModel->getAll();
$selectedId = (int) ($_GET['msg'] ?? 0);
$selected   = null;

if ($selectedId > 0) {
    foreach ($messages as $m) {
        if ((int) $m['id'] === $selectedId) {
            $selected = $m;
            break;
        }
    }
}

$pageTitle = 'Messages reçus';
require __DIR__ . '/includes/header.php';
?>

<!-- ============================================================
     LAYOUT 2 COLONNES : liste + détail
     ============================================================ -->
<div class="messages-layout">

    <!-- Colonne liste -->
    <div class="messages-list-col">

        <div class="messages-list-header">
            <h2>Boîte de réception</h2>
            <span class="messages-count"><?= count($messages) ?> message<?= count($messages) > 1 ? 's' : '' ?></span>
        </div>

        <?php if (empty($messages)): ?>
            <div class="messages-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <p>Aucun message pour le moment.</p>
            </div>
        <?php else: ?>

            <ul class="messages-list">
                <?php foreach ($messages as $m): ?>
                    <li>
                        <a href="/admin/contacts.php?msg=<?= (int) $m['id'] ?>"
                           class="message-item <?= (int) $m['id'] === $selectedId ? 'is-active' : '' ?> <?= (int) $m['lu'] === 0 ? 'is-unread' : '' ?>">

                            <div class="message-item-header">
                                <strong class="message-item-name">
                                    <?= htmlspecialchars($m['nom']) ?>
                                </strong>
                                <?php if ((int) $m['lu'] === 0): ?>
                                    <span class="message-item-dot"></span>
                                <?php endif; ?>
                            </div>

                            <div class="message-item-subject">
                                <?= htmlspecialchars($m['sujet']) ?>
                            </div>

                            <div class="message-item-preview">
                                <?= htmlspecialchars(mb_substr($m['message'], 0, 80)) ?><?= mb_strlen($m['message']) > 80 ? '…' : '' ?>
                            </div>

                            <div class="message-item-date">
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($m['created_at']))) ?>
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
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                <h3>Sélectionnez un message</h3>
                <p>Cliquez sur un message dans la liste pour l'afficher.</p>
            </div>

        <?php else: ?>

            <article class="message-detail">

                <header class="message-detail-header">
                    <div>
                        <h2><?= htmlspecialchars($selected['sujet']) ?></h2>
                        <p class="message-detail-date">
                            Reçu le <?= htmlspecialchars(date('d/m/Y à H:i', strtotime($selected['created_at']))) ?>
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
                        <?php else: ?>
                            <form method="post" class="form-inline">
                                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                <input type="hidden" name="action" value="mark_unread">
                                <input type="hidden" name="id" value="<?= (int) $selected['id'] ?>">
                                <button type="submit" class="btn-admin btn-admin--ghost btn-admin--sm">
                                    Marquer comme non lu
                                </button>
                            </form>
                        <?php endif; ?>

                        <form method="post" class="form-inline" data-confirm="Supprimer définitivement ce message ?">
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

                <!-- Expéditeur -->
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
                    <?php if (!empty($selected['ip'])): ?>
                        <span class="message-sender-ip">IP : <?= htmlspecialchars($selected['ip']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- Corps du message -->
                <div class="message-detail-body">
                    <?= nl2br(htmlspecialchars($selected['message'])) ?>
                </div>

                <!-- Actions rapides -->
                <footer class="message-detail-footer">
                    <a href="mailto:<?= htmlspecialchars($selected['email']) ?>?subject=Re: <?= htmlspecialchars(rawurlencode($selected['sujet'])) ?>"
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