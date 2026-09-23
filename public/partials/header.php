<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'CREAI-VBG';
$pageCss   = $pageCss   ?? null;   
$widePage  = $widePage  ?? false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="/images/favicon.svg">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/web-app-manifest-192x192.png">
    <link rel="icon" type="image/png" sizes="512x512" href="/images/web-app-manifest-512x512.png">
    <link rel="manifest" href="/images/site.webmanifest">
    <meta name="theme-color" content="#1d9e75">
    <?php if (!empty($noindex)): ?>
    <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
    <link rel="stylesheet" href="/css/base.css">
    <script src="/js/nav.js" defer></script>
    <script src="/js/password-toggle.js" defer></script>
    <script src="/js/piliers-carousel.js" defer></script>  
    <script src="/js/feature-slider.js" defer></script>
    <script src="/js/article.js" defer></script>
    <script src="/js/pillars-carousel.js" defer></script>
    <script src="/js/event-modal.js" defer></script>
    <script src="/js/equipe-tabs.js" defer></script>
    <script src="/js/don.js" defer></script>
    <script src="/js/wizard.js" defer></script>
    <?php if ($pageCss): ?>
        <link rel="stylesheet" href="/css/<?= htmlspecialchars($pageCss) ?>">
    <?php endif; ?>
</head>
<body>

<nav class="nav">
    <a href="/" class="logo" aria-label="Retour à l'accueil CREAI-VBG">
        <img src="/images/logo.png" alt="Logo CREAI-VBG">
        <span class="logo-text">
            <span class="logo-name">CREAI-VBG</span>
            <span class="logo-tagline">Centre de Recherche, d'Éducation et d'Action </br> Intégrée contre les Violences Base sur le Genre</span>
        </span>
    </a>

    <button type="button" class="nav-toggle" id="navToggle" aria-label="Ouvrir le menu" aria-expanded="false">
        <span class="nav-toggle-bar"></span>
        <span class="nav-toggle-bar"></span>
        <span class="nav-toggle-bar"></span>
    </button>

    <div class="nav-links" id="navLinks">
        <a href="/">Accueil</a>

        <div class="nav-item">
            <button type="button" class="nav-item-toggle" aria-haspopup="true" aria-expanded="false">
                Qui sommes-nous
                <svg class="nav-item-chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-submenu">
                <a href="/apropos.php">Notre association</a>
                <a href="/notre-equipe.php">Notre équipe</a>
            </div>
        </div>

        <a href="/piliers.php">Nos piliers</a>

        <div class="nav-item">
            <button type="button" class="nav-item-toggle" aria-haspopup="true" aria-expanded="false">
                Ressources
                <svg class="nav-item-chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-submenu">
                <a href="/actualites.php">Actualités</a>
                <a href="/connaissances.php">Centre de connaissances</a>
                <a href="/apprentissage.php">Centre d'apprentissage</a>
            </div>
        </div>

        <a href="/rejoindre.php">Nous rejoindre</a>
        <a href="/soutenir.php#don">Nous soutenir</a>
        <a href="/contact.php">Contact</a>
        <a href="/besoin-aide.php" class="nav-help">Besoin d'aide ?</a>
    </div>
</nav>
<?php if (empty($widePage)): ?>
    <main class="container">
<?php endif; ?>