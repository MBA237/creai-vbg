<?php
declare(strict_types=1);
session_start();

$pageTitle = 'CREAI-VBG — Recherche, éducation et action contre les VBG';
$pageCss   = 'index.css';
$widePage  = true;

require_once __DIR__ . '/../src/asset.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Article.php';
require_once __DIR__ . '/../src/Evenement.php';

// Tri par date desc → plus récents en premier.
// Base indisponible : la page d'accueil s'affiche quand même, sans actualités.
$articles   = Database::soft(static fn () => (new Article())->getRecent(10), []);
$evenements = Database::soft(static fn () => (new Evenement())->getRecent(5), []);

require __DIR__ . '/partials/header.php';
?>

<div class="home-page">

    <!-- ============================================================
         HERO + ACTUALITÉS + ÉVÉNEMENTS — carrousel unique
         Hero (5s) → chaque actualité (5s) → chaque événement (5s) → boucle
         ============================================================ -->
    <?php $slideIndex = 0; ?>
    <section class="feature-slider" id="mainSlider">
        <div class="feature-slider-track">

            <!-- Slide 0 : HERO -->
            <article class="feature-slide feature-slide--hero is-active" data-index="<?= $slideIndex++ ?>">
                <div class="home-hero">
                    <div class="home-hero-content">
                        <span class="home-badge">CREAI-VBG</span>
                        <h1>Recherche, éducation, innovation et accompagnement holistique</h1>
                        <p class="home-lead">
                            Le CREAI-VBG envisage une société juste en matière de genre, sans violence,
                            avec des relations saines, respectueuses et égales, et où toute personne
                            est libre de participer pleinement à la société.
                        </p>
                        <div class="home-hero-cta">
                            <?php require __DIR__ . '/partials/hero-don.php'; ?>
                            <a href="/rejoindre.php" class="btn btn--primary">Devenir membre</a>
                            <a href="/ce-que-nous-faisons.php" class="btn btn--outline">Découvrir nos actions</a>
                        </div>

                        <!-- Chiffres clés -->
                        <div class="home-stats-card">

                            <div class="home-stat">
                                <span class="home-stat-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                    </svg>
                                </span>
                                <span class="home-stat-text"><strong>24/7</strong> Assistant en ligne</span>
                            </div>

                            <div class="home-stat-divider"></div>

                            <div class="home-stat">
                                <span class="home-stat-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                        <polyline points="9 12 11 14 15 10"/>
                                    </svg>
                                </span>
                                <span class="home-stat-text"><strong>100%</strong> Confidentiel</span>
                            </div>

                            <div class="home-stat-divider"></div>

                            <div class="home-stat">
                                <span class="home-stat-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="4" y1="21" x2="20" y2="21"/>
                                        <line x1="6" y1="18" x2="6" y2="9"/>
                                        <line x1="10" y1="18" x2="10" y2="9"/>
                                        <line x1="14" y1="18" x2="14" y2="9"/>
                                        <line x1="18" y1="18" x2="18" y2="9"/>
                                        <polyline points="4 9 12 4 20 9"/>
                                    </svg>
                                </span>
                                <span class="home-stat-text"><strong>5</strong> Piliers d'action</span>
                            </div>

                        </div>
                    </div>
                </div>
            </article>

            <!-- Slides : ACTUALITÉS -->
            <?php foreach ($articles as $a):
                $datePub = $a['publie_le']
                    ? date('d M Y', strtotime($a['publie_le']))
                    : date('d M Y', strtotime($a['created_at']));
                $image = image_or($a['image'], '/images/articles/default.jpg');
            ?>
                <article class="feature-slide" data-index="<?= $slideIndex++ ?>">

                    <div class="feature-slide-bg">
                        <img src="<?= htmlspecialchars($image) ?>"
                             alt="<?= htmlspecialchars($a['titre']) ?>"
                             loading="lazy">
                        <div class="feature-slide-overlay"></div>
                    </div>
                    

                    <div class="feature-slide-content">
                        <div class="feature-slide-inner">

                            <div class="feature-slide-tags">
                                <span class="feature-slide-tag feature-slide-tag--teal">Actualité</span>
                                <?php if (!empty($a['categorie'])): ?>
                                    <span class="feature-slide-tag"><?= htmlspecialchars($a['categorie']) ?></span>
                                <?php endif; ?>
                                <span class="feature-slide-date"><?= htmlspecialchars($datePub) ?></span>
                            </div>

                            <h2 class="feature-slide-title"><?= htmlspecialchars($a['titre']) ?></h2>

                            <p class="feature-slide-excerpt"><?= htmlspecialchars($a['extrait']) ?></p>

                            <a href="/article.php?slug=<?= urlencode($a['slug']) ?>" class="feature-slide-btn">
                                Lire l'article
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </a>

                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

            <!-- Slides : ÉVÉNEMENTS -->
            <?php foreach ($evenements as $e):
                $dateDebut = strtotime($e['date_debut']);
                $dateFull  = date('d M Y', $dateDebut);
                $heure     = date('H:i', $dateDebut);
                $image     = image_or($e['image'], '/images/evenements/default.jpg');
            ?>
                <article class="feature-slide feature-slide--evenement" data-index="<?= $slideIndex++ ?>">

                    <div class="feature-slide-bg">
                        <img src="<?= htmlspecialchars($image) ?>"
                             alt="<?= htmlspecialchars($e['titre']) ?>"
                             loading="lazy">
                        <div class="feature-slide-overlay"></div>
                    </div>

                    <div class="feature-slide-content">
                        <div class="feature-slide-inner">

                            <div class="feature-slide-tags">
                                <span class="feature-slide-tag feature-slide-tag--purple">Événement</span>
                                <span class="feature-slide-date"><?= htmlspecialchars($dateFull) ?> · <?= htmlspecialchars($heure) ?></span>
                            </div>

                            <h2 class="feature-slide-title"><?= htmlspecialchars($e['titre']) ?></h2>

                            <p class="feature-slide-excerpt"><?= htmlspecialchars($e['description']) ?></p>

                            <div class="feature-slide-meta">
                                <span class="feature-slide-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <?= htmlspecialchars($e['lieu']) ?>
                                </span>
                            </div>

<button type="button"
        class="feature-slide-btn"
        data-event-modal
        data-event-id="<?= (int) $e['id'] ?>"
        data-event-titre="<?= htmlspecialchars($e['titre']) ?>"
        data-event-description="<?= htmlspecialchars($e['description']) ?>"
        data-event-contenu="<?= htmlspecialchars($e['contenu'] ?? '') ?>"
        data-event-lieu="<?= htmlspecialchars($e['lieu']) ?>"
        data-event-date-debut="<?= htmlspecialchars($e['date_debut']) ?>"
        data-event-date-fin="<?= htmlspecialchars($e['date_fin'] ?? '') ?>"
        data-event-image="<?= htmlspecialchars(image_or($e['image'], '/images/evenements/default.jpg')) ?>"
        data-event-slug="<?= htmlspecialchars($e['slug']) ?>">
    En savoir plus
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="5" y1="12" x2="19" y2="12"/>
        <polyline points="12 5 19 12 12 19"/>
    </svg>
</button>

                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

        </div>

        <button type="button" class="feature-slider-nav feature-slider-nav--prev" aria-label="Diapositive précédente">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </button>

        <button type="button" class="feature-slider-nav feature-slider-nav--next" aria-label="Diapositive suivante">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </button>

        <div class="feature-slider-dots">
            <?php for ($d = 0; $d < $slideIndex; $d++): ?>
                <button type="button"
                        class="feature-slider-dot <?= $d === 0 ? 'is-active' : '' ?>"
                        data-index="<?= $d ?>"
                        aria-label="Aller au slide <?= $d + 1 ?>"></button>
            <?php endfor; ?>
        </div>

        <div class="feature-slider-label">
            <a href="/actualites.php" class="feature-slider-more">
                Voir toutes nos actualités
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </a>
        </div>

    </section>

    <!-- ============================================================
         LE CONSTAT
         ============================================================ -->
    <section class="home-section home-section--alt">
        <div class="home-container home-container--full">
            <div class="constat-layout">
                <figure class="constat-media">
                    <img src="/images/constat.jpg" alt="Stop aux violences basées sur le genre" loading="lazy">
                </figure>

                <div class="constat-content">
                    <span class="eyebrow eyebrow--teal">Le constat</span>

                    <p class="home-text">
                        Les violences basées sur le genre (VBG) constituent un problème mondial
                        de droits humains, enraciné dans les inégalités de genre et les rapports
                        de force inégaux. Elles revêtent de nombreuses formes et causent des
                        préjudices à des millions de victimes, de familles et de communautés.
                        Bien qu'elles soient présentes dans toutes les sociétés, elles ne sont
                        pas une fatalité : <strong>les VBG sont évitables</strong>.
                    </p>

                    <p class="home-text">
                        Au Cameroun, elles restent généralisées, banalisées et trop souvent
                        invisibles, faute de données fiables, d'information publique suffisante,
                        d'investissement dans la prévention et de structures d'accompagnement
                        accessibles à toutes et tous.
                    </p>

                    <p class="home-text">
                        Face à ce constat, le CREAI-VBG agit sur tous les fronts à la fois :
                        <strong>comprendre par la science, transformer par l'éducation,
                        protéger par l'action</strong>. Parce que mettre fin aux VBG exige plus
                        qu'une réponse d'urgence — cela exige une stratégie.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         NOS 5 PILIERS D'ACTION — carrousel
         ============================================================ -->
    <section class="home-section">
        <div class="home-container">
            <div class="home-section-head">
                <span class="eyebrow eyebrow--teal">Nos 5 piliers d'action</span>
                <p class="home-section-intro">
                    Une approche à 360° pour briser durablement le cycle de la violence.
                </p>
            </div>
        </div>

        <div class="pillars-carousel">
            <button type="button" class="pillars-nav pillars-nav--prev" id="pillarsPrev" aria-label="Piliers précédents">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>

            <div class="pillars-scroll" id="pillarsScroll">
                <div class="pillars-track">
                    <a href="/piliers.php" class="pillar-card">
                        <div class="pillar-image">
                            <img src="/images/recherche.jpg" alt="Recherche et production de données" loading="lazy">
                        </div>
                        <div class="pillar-body">
                            <h3>Recherche &amp; Production de Données</h3>
                            <p>Produire des connaissances fondamentales et interdisciplinaires sur les causes, les contextes et les réponses efficaces à la VBG.</p>
                            <span class="pillar-link">En savoir plus →</span>
                        </div>
                    </a>

                    <a href="/piliers.php" class="pillar-card">
                        <div class="pillar-image">
                            <img src="/images/prevention.jpg" alt="Prévention et éducation communautaire" loading="lazy">
                        </div>
                        <div class="pillar-body">
                            <h3>Prévention &amp; Éducation Communautaire</h3>
                            <p>Prévenir la violence avant qu'elle ne survienne : École Itinérante, campagnes nationales et centre de formation.</p>
                            <span class="pillar-link">En savoir plus →</span>
                        </div>
                    </a>

                    <a href="/piliers.php" class="pillar-card">
                        <div class="pillar-image">
                            <img src="/images/innovation.jpg" alt="Innovation technologique" loading="lazy">
                        </div>
                        <div class="pillar-body">
                            <h3>Innovation technologique</h3>
                            <p>Chatbots, applications mobiles, plateformes de traçabilité et espaces mémoriels numériques au service de la lutte contre les VBG.</p>
                            <span class="pillar-link">En savoir plus →</span>
                        </div>
                    </a>

                    <a href="/piliers.php" class="pillar-card">
                        <div class="pillar-image">
                            <img src="/images/accompagnement.jpg" alt="Accompagnement holistique" loading="lazy">
                        </div>
                        <div class="pillar-body">
                            <h3>Accompagnement holistique</h3>
                            <p>Prise en charge des survivant(e)s et parcours de responsabilisation des auteur(e)s, pour prévenir la récidive.</p>
                            <span class="pillar-link">En savoir plus →</span>
                        </div>
                    </a>

                    <a href="/piliers.php" class="pillar-card">
                        <div class="pillar-image">
                            <img src="/images/Plaidoyer.jpg" alt="Plaidoyer et partenariats institutionnels" loading="lazy">
                        </div>
                        <div class="pillar-body">
                            <h3>Plaidoyer &amp; Partenariats</h3>
                            <p>Construire des partenariats institutionnels pour ancrer durablement une réponse multisectorielle aux VBG.</p>
                            <span class="pillar-link">En savoir plus →</span>
                        </div>
                    </a>
                </div>
            </div>

            <button type="button" class="pillars-nav pillars-nav--next" id="pillarsNext" aria-label="Piliers suivants">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
        </div>
    </section>

    <!-- ============================================================
         APPROCHE 360°
         ============================================================ -->
    <section class="home-section home-section--alt">
        <div class="home-container">
            <div class="home-section-head">
                <span class="eyebrow eyebrow--purple">Notre singularité</span>
                <p class="home-section-intro">
                    Une approche à 360° : des survivant(e)s accompagné(e)s jusqu'aux
                    auteur(e)s responsabilisé(e)s.
                </p>
            </div>

            <div class="approach-grid">

                <div class="approach-card">
                    <div class="approach-icon approach-icon--teal" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                        </svg>
                    </div>
                    <h3>Un ancrage scientifique</h3>
                    <p>Chaque intervention s'appuie sur des données probantes, validées par notre Comité Scientifique et Éthique.</p>
                </div>

                <div class="approach-card">
                    <div class="approach-icon approach-icon--purple" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <h3>Un engagement national</h3>
                    <p>Une action pensée pour tout le territoire camerounais, en lien avec les politiques publiques et les partenaires internationaux.</p>
                </div>

                <div class="approach-card">
                    <div class="approach-icon approach-icon--teal" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a9 9 0 1 1-9-9"/>
                            <polyline points="21 3 21 9 15 9"/>
                        </svg>
                    </div>
                    <h3>Une approche intégrée</h3>
                    <p>Recherche, éducation, innovation et action de terrain avancent ensemble — jamais l'une sans l'autre.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         URGENCE — Besoin d'aide ?
         ============================================================ -->
    <section class="home-emergency">
        <div class="home-container home-container--narrow">
            <div class="emergency-box">

                <div class="emergency-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M12 8v4"/>
                        <circle cx="12" cy="16" r="0.75" fill="currentColor" stroke="none"/>
                    </svg>
                </div>

                <div class="emergency-content">
                    <h2>Besoin d'aide ?</h2>
                    <p>
                        Vous êtes concerné(e) par une situation de violence basée sur
                        le genre ? Ne restez pas seul(e). Notre ligne d'écoute est
                        disponible du lundi au vendredi, de 9h00 à 17h00.
                    </p>
                    <div class="emergency-actions">
                        <a href="/besoin-aide.php" class="btn btn--coral">Demander de l'aide</a>
                        <a href="/signalement.php" class="btn btn--coral-outline">Signaler une situation</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         CTA FINAL
         ============================================================ -->
    <section class="home-cta">
        <div class="home-container">
            <h2>Il y a une place pour vous</h2>
            <p>
                Vous êtes chercheur(e), professionnel(le) de santé, juriste,
                éducateur(rice), ou simplement convaincu(e) que la lutte contre les
                VBG est l'affaire de tous ? Rejoignez le CREAI-VBG.
            </p>
            <div class="home-cta-buttons">
                <a href="/rejoindre.php" class="btn btn--primary">Devenir membre</a>
                <a href="/contact.php" class="btn btn--outline">Nous contacter</a>
            </div>
        </div>
    </section>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>