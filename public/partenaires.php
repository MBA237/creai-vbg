<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Partenaire.php';
require_once __DIR__ . '/partials/partenaires-helpers.php';

const PARTENAIRES_PAR_PAGE = 6;

$page   = max(1, (int) ($_GET['page'] ?? 1));
$result = Database::soft(
    static fn () => (new Partenaire())->paginate($page, PARTENAIRES_PAR_PAGE),
    ['items' => [], 'total' => 0, 'pages' => 1, 'page' => 1]
);
$partenaires = $result['items'];
$page        = $result['page'];
$pages       = $result['pages'];

$esc = static fn (mixed $s): string => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

$pageTitle = 'Nos partenaires — CREAI-VBG';
$pageCss   = 'partenaires.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="apropos-page">

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="apropos-hero">
    <div class="apropos-hero-content">
        <span class="apropos-badge">Nos partenaires</span>
        <h1>Ils s'engagent à nos côtés</h1>
        <p class="apropos-lead">
            Institutions, entreprises et organisations : ils partagent nos valeurs
            et soutiennent, avec nous, la lutte contre les violences basées sur le genre.
        </p>
        <div class="apropos-hero-actions">
            <?php require __DIR__ . '/partials/hero-don.php'; ?>
            <a href="/rejoindre.php#partenaires" class="btn-cta btn-cta--primary">Devenir partenaire</a>
            <a href="/apropos.php" class="btn-cta btn-cta--outline">Qui sommes-nous</a>
        </div>
    </div>
</section>

<!-- ============================================================
     LISTE DES PARTENAIRES — une bande pleine largeur par partenaire
     ============================================================ -->
<div id="liste"></div>

<?php if (!$partenaires): ?>
    <section class="apropos-section">
        <div class="apropos-container">
            <p class="partenaires-vide">La liste de nos partenaires sera bientôt disponible.</p>
        </div>
    </section>
<?php else: ?>
    <div class="partenaires-bandeau">
        <div class="partenaires-bandeau-inner">
            <span class="partenaires-total">
                <?= (int) $result['total'] ?> partenaire<?= $result['total'] > 1 ? 's' : '' ?>
            </span>
            <?php if ($pages > 1): ?>
                <span class="partenaires-page-info">Page <?= $page ?> sur <?= $pages ?></span>
            <?php endif; ?>
        </div>
    </div>

    <?php foreach ($partenaires as $i => $part): ?>
        <?php
        $urlPart = (string) ($part['site_web'] ?? '');
        $hasSite = (bool) preg_match('#^https?://#i', $urlPart);
        $domaine = $hasSite ? preg_replace('#^www\.#i', '', (string) parse_url($urlPart, PHP_URL_HOST)) : '';
        $numero  = ($page - 1) * PARTENAIRES_PAR_PAGE + $i + 1;
        ?>
        <section class="partenaire-band<?= $i % 2 === 1 ? ' partenaire-band--alt' : '' ?>" id="partenaire-<?= (int) $part['id'] ?>" aria-labelledby="partenaire-titre-<?= (int) $part['id'] ?>">
            <div class="partenaire-band-inner">

                <div class="partenaire-band-logo">
                    <div class="partenaire-logo"><?= partenaire_logo($part) ?></div>
                </div>

                <div class="partenaire-band-body">
                    <div class="partenaire-band-meta">
                        <span class="partenaire-band-num"><?= str_pad((string) $numero, 2, '0', STR_PAD_LEFT) ?></span>
                        <?php if (!empty($part['categorie'])): ?>
                            <span class="partenaire-detail-type"><?= $esc($part['categorie']) ?></span>
                        <?php endif; ?>
                    </div>

                    <h2 id="partenaire-titre-<?= (int) $part['id'] ?>"><?= $esc($part['nom']) ?></h2>

                    <?php if (!empty($part['description'])): ?>
                        <p class="partenaire-band-desc"><?= nl2br($esc($part['description'])) ?></p>
                    <?php endif; ?>

                    <?php if ($hasSite): ?>
                        <div class="partenaire-band-actions">
                            <a href="<?= $esc($urlPart) ?>" class="btn-cta btn-cta--solid btn-cta--lg" target="_blank" rel="noopener noreferrer"
                               aria-label="Visiter le site de <?= $esc($part['nom']) ?> (nouvel onglet)">
                                Visiter le site
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                    <polyline points="15 3 21 3 21 9"/>
                                    <line x1="10" y1="14" x2="21" y2="3"/>
                                </svg>
                            </a>
                            <span class="partenaire-band-domain">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="2" y1="12" x2="22" y2="12"/>
                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                </svg>
                                <?= $esc($domaine) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    <?php endforeach; ?>

    <?php if ($pages > 1): ?>
        <div class="partenaires-pager-zone">
            <nav class="partenaires-pager" aria-label="Pagination des partenaires">
                <?php if ($page > 1): ?>
                    <a class="partenaires-pager-btn partenaires-pager-btn--arrow" href="?page=<?= $page - 1 ?>#liste" aria-label="Page précédente">‹</a>
                <?php else: ?>
                    <span class="partenaires-pager-btn partenaires-pager-btn--arrow is-disabled" aria-hidden="true">‹</span>
                <?php endif; ?>

                <?php for ($n = 1; $n <= $pages; $n++): ?>
                    <?php if ($n === $page): ?>
                        <span class="partenaires-pager-btn is-current" aria-current="page"><?= $n ?></span>
                    <?php else: ?>
                        <a class="partenaires-pager-btn" href="?page=<?= $n ?>#liste" aria-label="Page <?= $n ?>"><?= $n ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $pages): ?>
                    <a class="partenaires-pager-btn partenaires-pager-btn--arrow" href="?page=<?= $page + 1 ?>#liste" aria-label="Page suivante">›</a>
                <?php else: ?>
                    <span class="partenaires-pager-btn partenaires-pager-btn--arrow is-disabled" aria-hidden="true">›</span>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
<?php endif; ?>

<section class="apropos-section partenaires-invite-section">
    <div class="apropos-container">
        <?php require __DIR__ . '/partials/partenaires-invite.php'; ?>
    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
