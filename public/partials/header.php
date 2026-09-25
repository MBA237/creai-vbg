<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../src/asset.php';

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
    <meta name="theme-color" content="#6a2c74">
    <?php if (!empty($noindex)): ?>
    <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= v('/css/base.css') ?>">
    <script src="<?= v('/js/nav.js') ?>" defer></script>
    <script src="<?= v('/js/password-toggle.js') ?>" defer></script>
    <script src="<?= v('/js/piliers-carousel.js') ?>" defer></script>  
    <script src="<?= v('/js/feature-slider.js') ?>" defer></script>
    <script src="<?= v('/js/pillars-carousel.js') ?>" defer></script>
    <script src="<?= v('/js/event-modal.js') ?>" defer></script>
    <script src="<?= v('/js/equipe-tabs.js') ?>" defer></script>
    <script src="<?= v('/js/don.js') ?>" defer></script>
    <script src="<?= v('/js/wizard.js') ?>" defer></script>
    <script src="<?= v('/js/quick-exit.js') ?>" defer></script>
    <?php if ($pageCss): ?>
        <?php foreach (css_chain($pageCss) as $cssFile): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars(v('/css/' . $cssFile)) ?>">
        <?php endforeach; ?>
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

    <?php
    // Onglet de la page courante : il garde l'apparence du survol (classe is-active)
    $navFile = basename((string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $navIs   = static fn (string ...$files): bool => in_array($navFile, $files, true);
    $navOn   = [
        'accueil'  => $navIs('index.php'),
        'apropos'  => $navIs('apropos.php'),
        'equipe'   => $navIs('notre-equipe.php'),
        'parten'   => $navIs('partenaires.php'),
        'piliers'  => $navIs('piliers.php') || str_starts_with($navFile, 'pilier-'),
        'actus'    => $navIs('actualites.php', 'article.php'),
        'connais'  => $navIs('connaissances.php'),
        'apprent'  => $navIs('apprentissage.php'),
        'rejoindre' => $navIs('rejoindre.php'),
        'soutenir' => $navIs('soutenir.php', 'don.php'),
        'contact'  => $navIs('contact.php'),
        'aide'     => $navIs('besoin-aide.php', 'signalement.php'),
    ];
    // Attributs d'un lien : classes + aria-current pour les lecteurs d'écran
    $navAttr = static fn (bool $on, string $class = ''): string =>
        ' class="' . trim($class . ($on ? ' is-active' : '')) . '"' . ($on ? ' aria-current="page"' : '');
    ?>
    <div class="nav-links" id="navLinks">
        <a href="/"<?= $navAttr($navOn['accueil']) ?>>Accueil</a>

        <div class="nav-item">
            <button type="button" class="nav-item-toggle<?= ($navOn['apropos'] || $navOn['equipe'] || $navOn['parten']) ? ' is-active' : '' ?>" aria-haspopup="true" aria-expanded="false">
                Qui sommes-nous
                <svg class="nav-item-chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-submenu">
                <a href="/apropos.php"<?= $navAttr($navOn['apropos']) ?>>Notre association</a>
                <a href="/notre-equipe.php"<?= $navAttr($navOn['equipe']) ?>>Notre équipe</a>
                <a href="/partenaires.php"<?= $navAttr($navOn['parten']) ?>>Nos partenaires</a>
            </div>
        </div>

        <a href="/piliers.php"<?= $navAttr($navOn['piliers']) ?>>Nos piliers</a>

        <div class="nav-item">
            <button type="button" class="nav-item-toggle<?= ($navOn['actus'] || $navOn['connais'] || $navOn['apprent']) ? ' is-active' : '' ?>" aria-haspopup="true" aria-expanded="false">
                Ressources
                <svg class="nav-item-chevron" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="nav-submenu">
                <a href="/actualites.php"<?= $navAttr($navOn['actus']) ?>>Actualités</a>
                <a href="/connaissances.php"<?= $navAttr($navOn['connais']) ?>>Centre de connaissances</a>
                <a href="/apprentissage.php"<?= $navAttr($navOn['apprent']) ?>>Centre d'apprentissage</a>
            </div>
        </div>

        <a href="/rejoindre.php"<?= $navAttr($navOn['rejoindre']) ?>>Nous rejoindre</a>
        <a href="/soutenir.php#don"<?= $navAttr($navOn['soutenir']) ?>>Nous soutenir</a>
        <a href="/contact.php"<?= $navAttr($navOn['contact']) ?>>Contact</a>
        <div class="nav-actions">
            <a href="/besoin-aide.php"<?= $navAttr($navOn['aide'], 'nav-help') ?>>Besoin d'aide ?</a>
            <a href="/don.php" class="nav-don" aria-label="Faire un don">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                Don
            </a>
        </div>
    </div>
            <!-- Quitter rapidement : un clic, ou 3 pressions sur Échap -->
<a href="https://www.google.com" class="quick-exit" data-quick-exit
   aria-label="Quitter rapidement ce site (ou appuyez 3 fois sur la touche Échap)"
   data-tooltip="Quitter rapidement">
    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" placeholder="Quitter rapidement">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
    </svg>
</a>

</nav>
<?php if (empty($widePage)): ?>
    <main class="container">
<?php endif; ?>