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
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow">La technologie au service de l'humain</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Nous avons la conviction que la technologie numérique a un potentiel
                important d'amélioration de la <strong>disponibilité</strong>, de
                l'<strong>accessibilité</strong> et de la <strong>qualité</strong>
                des services de lutte contre la VBG.
            </p>

            <p class="pilier-detail-text">
                La technologie nous permet d'être présents là où les dispositifs
                traditionnels ne peuvent pas toujours arriver — notamment dans les
                zones les plus reculées ou pour les personnes qui hésitent à
                s'exprimer en face à face.
            </p>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow">Nos outils numériques</span>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l8 3.2v5c0 5-3.5 9-8 10.5C7.5 19.2 4 15.2 4 10.2v-5L12 2z"/>
                            <path d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3>Outils pour la sécurité et le rétablissement</h3>
                    <p>
                        Application web et mobile pour informer les survivant(e)s,
                        soutenir la planification de la sécurité et mettre en
                        relation avec des services professionnels et par les pairs.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 21s7-6.5 7-11.5A7 7 0 1 0 5 9.5C5 14.5 12 21 12 21z"/>
                            <circle cx="12" cy="9.5" r="2.5"/>
                        </svg>
                    </div>
                    <h3>Cartographie interactive et géolocalisation mémorielle</h3>
                    <p>
                        Cartes interactives et bases de données sécurisées au service
                        de la mémoire des victimes de féminicides. Ces mémoriaux
                        virtuels luttent contre l'effacement des victimes.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h3>Chatbots et lignes d'assistance</h3>
                    <p>
                        Assistants numériques accessibles 24h/24 pour informer,
                        orienter et accompagner les personnes concernées par les VBG.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
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
            <div class="highlight-box highlight-box--purple">
                <h3>« Tech for Good » — la technologie comme moyen, jamais comme fin</h3>
                <p>
                    Nos outils numériques sont conçus <strong>avec et pour</strong> les
                    personnes concernées. Ils ne remplacent pas l'humain : ils
                    démultiplient sa portée et sa disponibilité.
                </p>
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Pilier précédent : Prévention
            </a>
            <a href="/pilier-accompagnement.php" class="pilier-nav-link pilier-nav-link--next">
                Pilier suivant : Accompagnement
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </nav>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>