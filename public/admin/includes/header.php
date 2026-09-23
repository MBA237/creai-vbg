<?php
/**
 * Header commun des pages admin.
 * Variables attendues :
 * @var string $pageTitle
 * @var array  $currentAdmin
 */
if (!isset($currentAdmin)) {
    die('Accès non autorisé.');
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($pageTitle ?? 'Administration') ?> — CREAI-VBG</title>

    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="stylesheet" href="/css/base.css">
    <link rel="stylesheet" href="/css/admin.css">
    <?php foreach (($extraCss ?? []) as $extraHref): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($extraHref) ?>">
    <?php endforeach; ?>
</head>
<body class="admin-body">

<div class="admin-layout">

    <!-- ============================================================
         SIDEBAR
         ============================================================ -->
    <aside class="admin-sidebar" id="adminSidebar">

        <div class="admin-sidebar-header">
            <a href="/admin/dashboard.php" class="admin-sidebar-brand">
                <span class="admin-sidebar-logo">CV</span>
                <span class="admin-sidebar-brand-text">
                    <strong>CREAI-VBG</strong>
                    <small>Administration</small>
                </span>
            </a>
            <button type="button" class="admin-sidebar-close" id="sidebarClose" aria-label="Fermer le menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <nav class="admin-nav">
            <span class="admin-nav-section">Tableau de bord</span>

            <a href="/admin/dashboard.php"
               class="admin-nav-link <?= $currentPage === 'dashboard' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9" rx="1"/>
                    <rect x="14" y="3" width="7" height="5" rx="1"/>
                    <rect x="14" y="12" width="7" height="9" rx="1"/>
                    <rect x="3" y="16" width="7" height="5" rx="1"/>
                </svg>
                Vue d'ensemble
            </a>

            <span class="admin-nav-section">Contenu</span>

            <a href="/admin/contacts.php"
               class="admin-nav-link <?= $currentPage === 'contacts' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <polyline points="22,6 12,13 2,6"/>
                </svg>
                Messages reçus
                <?php
                // Compteur de messages non lus
                require_once __DIR__ . '/../../../src/Contact.php';
                $unread = (new Contact())->countUnread();
                if ($unread > 0):
                ?>
                    <span class="admin-nav-badge"><?= (int) $unread ?></span>
                <?php endif; ?>
            </a>

            <a href="/admin/adhesions.php"
               class="admin-nav-link <?= $currentPage === 'adhesions' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <line x1="19" y1="8" x2="19" y2="14"/>
                    <line x1="22" y1="11" x2="16" y2="11"/>
                </svg>
                Adhésions
                <?php
                // Compteur de demandes en attente
                require_once __DIR__ . '/../../../src/Membership.php';
                $pendingAdhesions = (new Membership())->countPending();
                if ($pendingAdhesions > 0):
                ?>
                    <span class="admin-nav-badge"><?= (int) $pendingAdhesions ?></span>
                <?php endif; ?>
            </a>

            <a href="/admin/benevoles.php"
               class="admin-nav-link <?= $currentPage === 'benevoles' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Bénévoles
                <?php
                // Compteur de candidatures non lues
                require_once __DIR__ . '/../../../src/Benevole.php';
                $unreadBenevoles = (new Benevole())->countUnread();
                if ($unreadBenevoles > 0):
                ?>
                    <span class="admin-nav-badge"><?= (int) $unreadBenevoles ?></span>
                <?php endif; ?>
            </a>

            <?php
            // Signalements : données très sensibles, réservées aux administrateurs
            if (AdminAuth::hasRole('super_admin', 'admin')):
                require_once __DIR__ . '/../../../src/Signalement.php';
                $sigCounts = (new Signalement())->counts();
            ?>
            <a href="/admin/signalements.php"
               class="admin-nav-link <?= $currentPage === 'signalements' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Signalements
                <?php if ($sigCounts['nouveau'] > 0): ?>
                    <span class="admin-nav-badge"><?= (int) $sigCounts['nouveau'] ?></span>
                <?php endif; ?>
            </a>
            <?php endif; ?>

            <a href="/admin/membres.php"
               class="admin-nav-link <?= $currentPage === 'membres' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Membres de l'équipe
            </a>

            <a href="/admin/articles.php"
   class="admin-nav-link <?= $currentPage === 'articles' ? 'is-active' : '' ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
    </svg>
    Actualités
</a>

<a href="/admin/evenements.php"
   class="admin-nav-link <?= $currentPage === 'evenements' ? 'is-active' : '' ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/>
        <line x1="3" y1="10" x2="21" y2="10"/>
    </svg>
    Événements
</a>

<a href="/admin/apprentissages.php"
   class="admin-nav-link <?= $currentPage === 'apprentissages' ? 'is-active' : '' ?>">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
    </svg>
    Apprentissage
</a>

            <span class="admin-nav-section">Site</span>

            <a href="/" target="_blank" class="admin-nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                </svg>
                Voir le site
            </a>

            <span class="admin-nav-section">Compte</span>

            <?php if (AdminAuth::hasRole('super_admin')): ?>
            <a href="/admin/admins.php"
               class="admin-nav-link <?= $currentPage === 'admins' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <circle cx="12" cy="10" r="2.5"/>
                    <path d="M7.5 17c.8-2 2.6-3 4.5-3s3.7 1 4.5 3"/>
                </svg>
                Administrateurs
            </a>
            <?php endif; ?>

            <a href="/admin/mon-compte.php"
               class="admin-nav-link <?= $currentPage === 'mon-compte' ? 'is-active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Mon compte
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <div class="admin-user-info">
                <div class="admin-user-avatar">
                    <?= htmlspecialchars(mb_substr($currentAdmin['nom'], 0, 1)) ?>
                </div>
                <div class="admin-user-details">
                    <strong><?= htmlspecialchars($currentAdmin['nom']) ?></strong>
                    <span><?= htmlspecialchars($currentAdmin['role']) ?></span>
                </div>
            </div>
            <a href="/logout-admin.php" class="admin-logout-btn" title="Déconnexion">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
    </aside>

    <!-- Overlay pour mobile -->
    <div class="admin-overlay" id="adminOverlay"></div>

    <!-- ============================================================
         CONTENU PRINCIPAL
         ============================================================ -->
    <main class="admin-main">

        <header class="admin-topbar">
            <button type="button" class="admin-topbar-toggle" id="sidebarToggle" aria-label="Ouvrir le menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            <h1 class="admin-topbar-title"><?= htmlspecialchars($pageTitle ?? '') ?></h1>

            <div class="admin-topbar-actions">
                <a href="/admin/dashboard.php" class="admin-topbar-btn" title="Tableau de bord">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="9" rx="1"/>
                        <rect x="14" y="3" width="7" height="5" rx="1"/>
                        <rect x="14" y="12" width="7" height="9" rx="1"/>
                        <rect x="3" y="16" width="7" height="5" rx="1"/>
                    </svg>
                </a>
            </div>
        </header>

        <div class="admin-content">