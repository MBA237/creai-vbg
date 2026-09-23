<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Contact.php';
require_once __DIR__ . '/../../src/Membre.php';
require_once __DIR__ . '/../../src/Membership.php';

$contactModel = new Contact();
$membreModel  = new Membre();

$membershipModel  = new Membership();
$adhesionCounts   = $membershipModel->countByStatut();
$pendingAdhesions = $adhesionCounts['en_attente'];
$recentAdhesions  = $membershipModel->getRecent(6);

$adhesionLabels = ['en_attente' => 'En attente', 'agree' => 'Agréée', 'refuse' => 'Refusée'];
$adhesionCats   = Membership::CATEGORIES;

$unread   = $contactModel->countUnread();
$messages = $contactModel->getAll();
$membres  = $membreModel->getAll();

// Signalements : réservés aux administrateurs
$canSeeSignalements = AdminAuth::hasRole('super_admin', 'admin');
$sigCounts = ['nouveau' => 0, 'danger_nouveau' => 0];
if ($canSeeSignalements) {
    require_once __DIR__ . '/../../src/Signalement.php';
    $sigCounts = (new Signalement())->counts();
}

$pageTitle = 'Tableau de bord';
require __DIR__ . '/includes/header.php';
?>

<div class="dashboard-grid">

    <?php if ($canSeeSignalements): ?>
    <a href="/admin/signalements.php?statut=nouveau" class="dashboard-card <?= $sigCounts['nouveau'] > 0 ? 'dashboard-card--alert' : '' ?>">
        <div class="dashboard-card-icon dashboard-card-icon--coral">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div class="dashboard-card-content">
            <span class="dashboard-card-number"><?= (int) $sigCounts['nouveau'] ?></span>
            <span class="dashboard-card-label">Nouveau<?= $sigCounts['nouveau'] > 1 ? 'x' : '' ?> signalement<?= $sigCounts['nouveau'] > 1 ? 's' : '' ?></span>
            <?php if ($sigCounts['danger_nouveau'] > 0): ?>
                <span class="dashboard-card-badge"><?= (int) $sigCounts['danger_nouveau'] ?> en danger immédiat</span>
            <?php elseif ($sigCounts['nouveau'] > 0): ?>
                <span class="dashboard-card-badge">À lire</span>
            <?php endif; ?>
        </div>
    </a>
    <?php endif; ?>

    <a href="/admin/contacts.php" class="dashboard-card <?= $unread > 0 ? 'dashboard-card--alert' : '' ?>">
        <div class="dashboard-card-icon dashboard-card-icon--teal">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        <div class="dashboard-card-content">
            <span class="dashboard-card-number"><?= count($messages) ?></span>
            <span class="dashboard-card-label">Messages reçus</span>
            <?php if ($unread > 0): ?>
                <span class="dashboard-card-badge"><?= (int) $unread ?> non lu<?= $unread > 1 ? 's' : '' ?></span>
            <?php endif; ?>
        </div>
    </a>

    <a href="/admin/adhesions.php" class="dashboard-card <?= $pendingAdhesions > 0 ? 'dashboard-card--alert' : '' ?>">
        <div class="dashboard-card-icon dashboard-card-icon--coral">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <line x1="19" y1="8" x2="19" y2="14"/>
                <line x1="22" y1="11" x2="16" y2="11"/>
            </svg>
        </div>
        <div class="dashboard-card-content">
            <span class="dashboard-card-number"><?= (int) $pendingAdhesions ?></span>
            <span class="dashboard-card-label">Demande<?= $pendingAdhesions > 1 ? 's' : '' ?> d'adhésion en attente</span>
            <?php if ($pendingAdhesions > 0): ?>
                <span class="dashboard-card-badge">À examiner</span>
            <?php endif; ?>
        </div>
    </a>

    <a href="/admin/adhesions.php?statut=agree" class="dashboard-card">
        <div class="dashboard-card-icon dashboard-card-icon--teal">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <polyline points="16 11 18 13 22 9"/>
            </svg>
        </div>
        <div class="dashboard-card-content">
            <span class="dashboard-card-number"><?= (int) $adhesionCounts['agree'] ?></span>
            <span class="dashboard-card-label">Membre<?= $adhesionCounts['agree'] > 1 ? 's' : '' ?> adhérent<?= $adhesionCounts['agree'] > 1 ? 's' : '' ?> (agréé<?= $adhesionCounts['agree'] > 1 ? 's' : '' ?>)</span>
        </div>
    </a>

    <a href="/admin/membres.php" class="dashboard-card">
        <div class="dashboard-card-icon dashboard-card-icon--purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="dashboard-card-content">
            <span class="dashboard-card-number"><?= count($membres) ?></span>
            <span class="dashboard-card-label">Membres de l'équipe</span>
        </div>
    </a>

    <a href="/" target="_blank" class="dashboard-card">
        <div class="dashboard-card-icon dashboard-card-icon--gray">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                <polyline points="15 3 21 3 21 9"/>
                <line x1="10" y1="14" x2="21" y2="3"/>
            </svg>
        </div>
        <div class="dashboard-card-content">
            <span class="dashboard-card-number">↗</span>
            <span class="dashboard-card-label">Voir le site public</span>
        </div>
    </a>

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
                                <strong><?= htmlspecialchars($d['nom']) ?></strong>
                                <br>
                                <small class="admin-text-muted"><?= htmlspecialchars($d['email']) ?></small>
                            </td>
                            <td><?= htmlspecialchars($adhesionCats[$d['categorie']] ?? $d['categorie']) ?></td>
                            <td>
                                <span class="adhesion-badge adhesion-badge--<?= htmlspecialchars($d['statut']) ?>">
                                    <?= htmlspecialchars($adhesionLabels[$d['statut']] ?? $d['statut']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($d['created_at']))) ?></td>
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