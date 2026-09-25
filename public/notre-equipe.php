<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/Pole.php';
require_once __DIR__ . '/../src/Membre.php';

$poles         = [];
$membresByPole = [];

// Base indisponible : la page s'affiche quand même, sans les membres.
$poles         = Database::soft(static fn () => (new Pole())->getAll(), []);
$membresByPole = Database::soft(static fn () => (new Membre())->getAllGroupedByPole(), []);

$pageTitle = 'Notre équipe — CREAI-VBG';
$pageCss   = 'notre-equipe.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="apropos-page">

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="apropos-hero">
    <div class="apropos-hero-content">
        <span class="apropos-badge">Notre équipe</span>
        <h1>Celles et ceux qui font vivre le CREAI-VBG</h1>
        <p class="apropos-lead">
            Sept pôles organisent notre action au quotidien, du Bureau à l'innovation
            numérique. Découvrez les personnes qui les composent.
        </p>

        <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
    </div>
</section>

<?php if (empty($poles)): ?>

<section class="apropos-section">
    <div class="apropos-container">
        <p class="equipe-empty">L'équipe sera bientôt présentée.</p>
    </div>
</section>

<?php else: ?>

<!-- ============================================================
     NAVIGATION PAR PÔLE
     ============================================================ -->
<nav class="team-nav" aria-label="Pôles de l'équipe">
    <ul class="team-nav-list">
        <?php foreach ($poles as $pole): ?>
            <li>
                <a class="team-nav-link" href="#pole-<?= htmlspecialchars($pole['slug']) ?>">
                    <?= htmlspecialchars($pole['nom']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<!-- ============================================================
     UNE SECTION PAR PÔLE
     ============================================================ -->
<?php foreach ($poles as $i => $pole):
    $slug    = $pole['slug'];
    $membres = $membresByPole[$slug] ?? [];
?>
<section class="apropos-section pole-section <?= $i % 2 === 1 ? 'apropos-section--alt' : '' ?>"
         id="pole-<?= htmlspecialchars($slug) ?>">
    <div class="apropos-container">
        <h2 class="pole-title"><?= htmlspecialchars($pole['nom']) ?></h2>

        <?php if (empty($membres)): ?>
            <p class="equipe-empty">Les membres de ce pôle seront bientôt présentés.</p>
        <?php else: ?>
            <div class="membres-grid">
                <?php foreach ($membres as $m): ?>
                    <article class="membre-card">
                        <div class="membre-photo">
                            <img src="<?= htmlspecialchars(image_or($m['photo'], '/images/team/default-avatar.jpg')) ?>"
                                 alt="<?= htmlspecialchars($m['nom']) ?>"
                                 loading="lazy">
                        </div>
                        <div class="membre-body">
                            <h3 class="membre-nom"><?= htmlspecialchars($m['nom']) ?></h3>
                            <?php if (!empty($m['poste'])): ?>
                                <p class="membre-poste"><?= htmlspecialchars($m['poste']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($m['profession'])): ?>
                                <p class="membre-profession"><?= htmlspecialchars($m['profession']) ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endforeach; ?>

<?php endif; ?>

<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="apropos-cta">
    <div class="apropos-container">
        <h2>Il y a une place pour vous</h2>
        <p>
            Chercheur(e), professionnel(le) de santé, juriste, éducateur(rice),
            ou simplement convaincu(e) que la lutte contre les VBG est l'affaire
            de tous : rejoignez le CREAI-VBG.
        </p>
        <div class="cta-buttons">
            <a href="/rejoindre.php" class="btn-cta btn-cta--primary">Devenir membre</a>
            <a href="/apropos.php" class="btn-cta btn-cta--outline">Qui sommes-nous</a>
        </div>
    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
