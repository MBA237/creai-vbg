<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/Apprentissage.php';

// Filtre par type : /apprentissage.php?type=webinaire
$type = (string) ($_GET['type'] ?? '');
if (!isset(Apprentissage::TYPES[$type])) {
    $type = '';
}

$items  = [];
$counts = array_fill_keys(array_keys(Apprentissage::TYPES), 0);
try {
    $model  = new Apprentissage();
    $counts = $model->countPublishedByType();
    $items  = $model->getPublished($type !== '' ? $type : null);
} catch (Throwable $e) {
    error_log('[apprentissage.php] ' . $e->getMessage());
}
$total = array_sum($counts);

$mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
function dateFr(string $ymd, array $mois): string
{
    $t = strtotime($ymd);
    return $t ? (int) date('j', $t) . ' ' . $mois[(int) date('n', $t)] . ' ' . date('Y', $t) : '';
}

$pageTitle = 'Centre d\'apprentissage — CREAI-VBG';
$pageCss   = 'apprentissages.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="page-content">

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="page-hero">
    <div class="page-hero-content">
        <span class="page-badge">Centre d'apprentissage</span>
        <h1>Apprendre, échanger, renforcer ses compétences</h1>
        <p class="page-lead">
            Découvrez des ressources sélectionnées, des webinaires, des cours gratuits
            à votre rythme, des échanges entre pairs et des opportunités d'apprentissage
            pour renforcer vos connaissances et vos compétences en matière de prévention
            des violences sexistes.
        </p>

        <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
    </div>
</section>

<!-- ============================================================
     LIENS D'APPRENTISSAGE (créés par l'admin)
     ============================================================ -->
<section class="page-section" id="formations">
    <div class="page-container">
        <h2 class="section-title">Formations et liens d'apprentissage</h2>

        <?php if ($total === 0): ?>

            <div class="notice-soon">
                <h3>Le Centre d'apprentissage ouvre progressivement</h3>
                <p>
                    Les premiers liens de formation seront publiés ici prochainement.
                    Vous souhaitez être informé(e) ou proposer un sujet ? Écrivez-nous.
                </p>
                <div class="notice-actions">
                    <a href="/actualites.php" class="btn-cta btn-cta--primary">Voir les actualités</a>
                    <a href="/contact.php" class="btn-cta btn-cta--ghost">Nous écrire</a>
                </div>
            </div>

        <?php else: ?>

            <p class="section-intro">
                Des formations choisies et partagées par le CREAI-VBG. Chaque lien vous mène directement à la formation.
            </p>

            <!-- Filtres par type -->
            <nav class="learn-filters" aria-label="Filtrer par type">
                <a href="/apprentissage.php#formations" class="learn-filter <?= $type === '' ? 'is-active' : '' ?>">
                    Tout <span><?= (int) $total ?></span>
                </a>
                <?php foreach (Apprentissage::TYPES as $value => $label): ?>
                    <?php if ($counts[$value] > 0): ?>
                        <a href="/apprentissage.php?type=<?= urlencode($value) ?>#formations"
                           class="learn-filter <?= $type === $value ? 'is-active' : '' ?>">
                            <?= htmlspecialchars($label) ?> <span><?= (int) $counts[$value] ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

            <?php if (empty($items)): ?>
                <p class="notice-soon">Aucun lien de ce type pour le moment.</p>
            <?php else: ?>
                <div class="learn-grid">
                    <?php foreach ($items as $it): ?>
                        <article class="learn-card learn-card--<?= htmlspecialchars($it['type']) ?>" id="formation-<?= (int) $it['id'] ?>">

                            <div class="learn-card-top">
                                <span class="learn-type"><?= htmlspecialchars(Apprentissage::TYPES[$it['type']] ?? $it['type']) ?></span>
                                <?php $langs = Apprentissage::parseLangues($it['langue']); ?>
                                <span class="learn-langs"
                                      title="Disponible en : <?= htmlspecialchars(implode(' et ', array_map(fn($l) => Apprentissage::LANGUES[$l], $langs))) ?>">
                                    <?php foreach ($langs as $l): ?>
                                        <span class="learn-lang"><?= htmlspecialchars(strtoupper($l)) ?></span>
                                    <?php endforeach; ?>
                                </span>
                            </div>

                            <h3 title="<?= htmlspecialchars($it['titre']) ?>"><?= htmlspecialchars($it['titre']) ?></h3>
                            <p class="learn-desc" title="<?= htmlspecialchars($it['description']) ?>"><?= htmlspecialchars($it['description']) ?></p>

                            <p class="learn-meta"><?php if (!empty($it['source'])): ?><span><?= htmlspecialchars($it['source']) ?></span><?php endif; ?><?php if (!empty($it['date_formation'])): ?><span><?= htmlspecialchars(dateFr($it['date_formation'], $mois)) ?></span><?php endif; ?></p>

                            <div class="learn-actions">
                                <a href="<?= htmlspecialchars($it['url']) ?>"
                                   class="btn-cta btn-cta--primary btn-cta--sm"
                                   target="_blank" rel="noopener noreferrer">
                                    Accéder à la formation
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <polyline points="15 3 21 3 21 9"/>
                                        <line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </a>

                                <button type="button" class="learn-share"
                                        data-share
                                        data-title="<?= htmlspecialchars($it['titre']) ?>"
                                        data-anchor="formation-<?= (int) $it['id'] ?>"
                                        title="Partager cette formation"
                                        aria-label="Partager « <?= htmlspecialchars($it['titre']) ?> »">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="18" cy="5" r="3"/>
                                        <circle cx="6" cy="12" r="3"/>
                                        <circle cx="18" cy="19" r="3"/>
                                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                                    </svg>
                                </button>
                            </div>

                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<!-- ============================================================
     5 FORMATS D'APPRENTISSAGE
     ============================================================ -->
<section class="page-section page-section--alt">
    <div class="page-container">
        <h2 class="section-title">Nos formats d'apprentissage</h2>
        <p class="section-intro">
            Que vous soyez praticien(ne), étudiant(e), bénévole ou membre d'une organisation,
            choisissez le format qui vous convient.
        </p>

        <div class="cards-grid cards-grid--3">

            <article class="info-card info-card--teal">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <h3>Ressources sélectionnées</h3>
                <p>Une sélection de contenus choisis pour renforcer vos connaissances en prévention des violences sexistes.</p>
                <?php if ($counts['ressource'] > 0): ?>
                    <a href="/apprentissage.php?type=ressource#formations" class="info-card-link">Voir les liens <?= (int) $counts['ressource'] ?> →</a>
                <?php endif; ?>
                
            </article>

            <article class="info-card info-card--purple">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="7" width="15" height="10" rx="2"/>
                        <polygon points="22 8 17 12 22 16 22 8"/>
                    </svg>
                </div>
                <h3>Webinaires</h3>
                <p>Des rendez-vous en ligne pour apprendre auprès d'expert(e)s et de praticien(ne)s.</p>
                <?php if ($counts['webinaire'] > 0): ?>
                    <a href="/apprentissage.php?type=webinaire#formations" class="info-card-link">Voir les liens <?= (int) $counts['webinaire'] ?> →</a>
                <?php endif; ?>
                
            </article>

            <article class="info-card info-card--coral">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                        <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <h3>Cours gratuits, à votre rythme</h3>
                <p>Des cours accessibles gratuitement, que vous suivez quand vous le souhaitez.</p>
                <?php if ($counts['cours'] > 0): ?>
                    <a href="/apprentissage.php?type=cours#formations" class="info-card-link">Voir les liens <?= (int) $counts['cours'] ?> →</a>
                <?php endif; ?>
            </article>

            <article class="info-card info-card--gray">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Échanges entre pairs</h3>
                <p>Des espaces pour partager vos pratiques et apprendre des autres professionnel(le)s.</p>
                <?php if ($counts['pairs'] > 0): ?>
                    <a href="/apprentissage.php?type=pairs#formations" class="info-card-link">Voir les liens <?= (int) $counts['pairs'] ?> →</a>
                <?php endif; ?>
            </article>

            <article class="info-card info-card--teal">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                </div>
                <h3>Opportunités d'apprentissage</h3>
                <p>Ateliers, formations et autres occasions de monter en compétences.</p>
                <?php if ($counts['opportunite'] > 0): ?>
                    <a href="/apprentissage.php?type=opportunite#formations" class="info-card-link">Voir les liens (<?= (int) $counts['opportunite'] ?>) →</a>
                <?php endif; ?>
                <a href="/pilier-prevention.php" class="info-card-link">Notre Centre de formation →</a>
            </article>

        </div>
    </div>
</section>

<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="page-cta">
    <div class="page-container">
        <h2>Rejoignez une communauté qui apprend</h2>
        <p>
            En devenant membre du CREAI-VBG, vous participez à nos activités
            et contribuez à la vie de l'association.
        </p>
        <div class="cta-buttons">
            <a href="/rejoindre.php" class="btn-cta btn-cta--primary">Nous rejoindre</a>
            <a href="/connaissances.php" class="btn-cta btn-cta--ghost">Centre de connaissances</a>
        </div>
    </div>
</section>

</main>

<script src="<?= v('/js/apprentissage.js') ?>" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
