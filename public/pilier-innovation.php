<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Pilier 3 — Innovation technologique';
$pageCss   = 'pilier-detail.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="pilier-detail-page">

    <section class="pilier-detail-hero">
        <div class="pilier-detail-hero-content">

            <nav class="pilier-detail-breadcrumb">
                <a href="/">Accueil</a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <a href="/piliers.php">Nos piliers</a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <span>Innovation</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="14" rx="2"/>
                    <path d="M8 21h8M12 17v4"/>
                </svg>
                Pilier 3
            </span>

            <h1>Innovation technologique</h1>

            <p class="pilier-detail-lead">
                Tirer parti des outils numériques pour améliorer les services de
                prévention et de réponse à la violence basée sur le genre.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container">

            <div class="pilier-feature pilier-feature--reverse">

                <div class="pilier-feature-content">
                    <span class="duo-card-tag">Innovation technologique</span>
                    <h2>La technologie au service de l'humain</h2>

                    <p class="pilier-detail-text pilier-detail-text--lead">
                        Nous avons la conviction que la technologie numérique a un
                        potentiel important d'amélioration de la
                        <strong>disponibilité</strong>, de
                        l'<strong>accessibilité</strong> et de la
                        <strong>qualité</strong> des services de lutte contre la VBG.
                    </p>

                    <p class="pilier-detail-text">
                        La technologie nous permet d'être présents là où les
                        dispositifs traditionnels ne peuvent pas toujours arriver —
                        notamment dans les <strong>zones les plus reculées</strong>
                        ou pour les personnes qui hésitent à s'exprimer
                        <strong>en face à face</strong>.
                    </p>

                    <div class="intro-split-actions" style="margin-top: 26px;">
                        <a href="#outils" class="btn btn--primary">
                            Découvrir nos outils
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <a href="/contact.php" class="btn btn--outline">Contacter l'équipe innovation</a>
                    </div>
                </div>

                <div class="pilier-feature-image">
                    <img src="/images/innovation.jpg" alt="Innovation technologique au service de la lutte contre les violences basées sur le genre" loading="lazy">
                </div>

            </div>

            <!-- Les trois bénéfices attendus de la technologie -->
            <h3 class="tile-grid-title">Trois axes d'amélioration des services</h3>
            <div class="tile-grid">
                <div class="tile">
                    <span class="tile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </span>
                    Disponibilité
                </div>
                <div class="tile">
                    <span class="tile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </span>
                    Accessibilité
                </div>
                <div class="tile">
                    <span class="tile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l8 3.2v5c0 5-3.5 9-8 10.5C7.5 19.2 4 15.2 4 10.2v-5L12 2z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </span>
                    Qualité
                </div>
            </div>

        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt" id="outils">
        <div class="pilier-detail-container">
            <span class="eyebrow">Nos outils numériques</span>

            <p class="pilier-detail-text pilier-detail-text--lead" style="max-width: 68ch; margin-bottom: 32px;">
                <strong>Quatre familles d'outils</strong> au service de la
                prévention et de la réponse aux VBG.
            </p>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l8 3.2v5c0 5-3.5 9-8 10.5C7.5 19.2 4 15.2 4 10.2v-5L12 2z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Application web et mobile</span>
                    <h3>Outils pour la sécurité et le rétablissement</h3>
                    <p>
                        Application web et mobile pour informer les survivant(e)s,
                        soutenir la planification de la sécurité et mettre en
                        relation avec des services professionnels et par les pairs.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21s7-6.5 7-11.5A7 7 0 1 0 5 9.5C5 14.5 12 21 12 21z"/>
                            <circle cx="12" cy="9.5" r="2.5"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Mémoire des victimes</span>
                    <h3>Cartographie interactive et géolocalisation mémorielle</h3>
                    <p>
                        Cartes interactives et bases de données sécurisées au service
                        de la mémoire des victimes de féminicides. Ces mémoriaux
                        virtuels luttent contre l'effacement des victimes.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Assistance 24h/24</span>
                    <h3>Chatbots et lignes d'assistance</h3>
                    <p>
                        Assistants numériques accessibles 24h/24 pour informer,
                        orienter et accompagner les personnes concernées par les VBG.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Jeunes et activistes</span>
                    <h3>Formation des leaders numériques</h3>
                    <p>
                        Programme de formation des jeunes et activistes à l'utilisation
                        stratégique des plateformes numériques pour créer des contenus
                        éducatifs et déconstruire les mythes.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <div class="info-highlight">
                <div class="info-highlight-row">
                    <div class="info-highlight-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18h6"/>
                            <path d="M10 22h4"/>
                            <path d="M12 2a7 7 0 0 0-4 12.72V17h8v-2.28A7 7 0 0 0 12 2z"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <h3>« Tech for Good » — la technologie comme moyen, jamais comme fin</h3>
                        <p>
                            Nos outils numériques sont conçus <strong>avec et
                            pour</strong> les personnes concernées. Ils ne
                            remplacent pas l'humain : ils démultiplient sa
                            portée et sa disponibilité.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Découvrir nos solutions numériques</h2>
            <p>
                Vous souhaitez en savoir plus sur nos outils, ou collaborer à leur
                développement ? Contactez notre équipe innovation.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/contact.php" class="btn btn--primary">Nous contacter</a>
                <a href="/piliers.php" class="btn btn--outline">Voir tous nos piliers</a>
            </div>
        </div>
    </section>

    <div class="pilier-detail-container">
        <nav class="pilier-nav-links">
            <a href="/pilier-prevention.php" class="pilier-nav-link">
                <span class="pilier-nav-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </span>
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Pilier précédent</span>
                    <span class="pilier-nav-title">Prévention</span>
                </span>
            </a>
            <a href="/pilier-accompagnement.php" class="pilier-nav-link pilier-nav-link--next">
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Pilier suivant</span>
                    <span class="pilier-nav-title">Accompagnement</span>
                </span>
                <span class="pilier-nav-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </span>
            </a>
        </nav>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>