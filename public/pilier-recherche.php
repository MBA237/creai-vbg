<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Pilier 1 — Recherche & Production de Données';
$pageCss   = 'pilier-detail.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="pilier-detail-page">

    <!-- HERO -->
    <section class="pilier-detail-hero">
        <div class="pilier-detail-hero-content">

            <nav class="pilier-detail-breadcrumb" aria-label="Fil d'Ariane">
                <a href="/">Accueil</a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <a href="/piliers.php">Nos piliers</a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <span>Recherche</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 2h6v6l4 10H5L9 8V2z"/>
                    <path d="M9 2h6"/>
                </svg>
                Pilier 1
            </span>

            <h1>Recherche &amp; Production de Données</h1>

            <p class="pilier-detail-lead">
                Produire des connaissances fondamentales et interdisciplinaires sur
                les causes, les contextes et les réponses les plus efficaces à la
                violence basée sur le genre.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <!-- INTRO -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow">Éclairer par la science</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Nous produisons des connaissances fondamentales et interdisciplinaires
                sur les causes, les contextes et les réponses les plus efficaces à la
                violence.
            </p>

            <p class="pilier-detail-text">
                Ces recherches sont intégrées dans nos solutions innovantes et notre
                renforcement des capacités, afin de favoriser l'élimination de la
                violence de genre. Nous menons des recherches qui éclairent les
                pratiques — notamment par le biais de programmes éducatifs — et nous
                utilisons les enseignements tirés de ces actions pour orienter nos
                initiatives de recherche.
            </p>

            <p class="pilier-detail-text">
                Ce pilier fonde la crédibilité et la pertinence de tout ce que nous
                entreprenons : <strong>on ne combat efficacement que ce que l'on
                comprend précisément</strong>.
            </p>
        </div>
    </section>

    <!-- PROGRAMMES -->
    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow">Nos axes de recherche</span>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <h3>Études empiriques</h3>
                    <p>
                        Enquêtes de terrain, entretiens approfondis et analyses
                        quantitatives pour mieux comprendre les dynamiques de
                        violence au Cameroun.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <h3>Recherches interdisciplinaires</h3>
                    <p>
                        Croisement des regards sociologique, juridique, psychologique
                        et de santé publique pour saisir la complexité des VBG.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v20"/>
                            <path d="M5 7h14"/>
                            <path d="M5 7l-3 7a3.5 3.5 0 0 0 6 0L5 7z"/>
                            <path d="M19 7l-3 7a3.5 3.5 0 0 0 6 0l-3-7z"/>
                        </svg>
                    </div>
                    <h3>Évaluation des politiques publiques</h3>
                    <p>
                        Analyser l'efficacité des dispositifs existants et formuler
                        des recommandations fondées sur les preuves.
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
                    <h3>Partenariats académiques</h3>
                    <p>
                        Collaborations avec des institutions nationales et
                        internationales pour mutualiser les savoirs et les méthodes.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <!-- MISE EN AVANT -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <div class="highlight-box">
                <h3>Un ancrage scientifique au service de l'action</h3>
                <p>
                    Chaque intervention du CREAI-VBG s'appuie sur des données probantes.
                    Nos recherches ne restent pas dans les tiroirs : elles nourrissent
                    directement nos programmes de prévention, nos outils numériques
                    et nos actions de terrain.
                </p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Collaborer avec notre équipe de recherche</h2>
            <p>
                Chercheur(e), universitaire, institution ou organisation de la société
                civile : nous sommes ouverts aux partenariats de recherche.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/contact.php" class="btn btn--primary">Nous contacter</a>
                <a href="/piliers.php" class="btn btn--outline">Voir tous nos piliers</a>
            </div>
        </div>
    </section>

    <!-- NAVIGATION -->
    <div class="pilier-detail-container">
        <nav class="pilier-nav-links">
            <a href="/piliers.php" class="pilier-nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Retour aux piliers
            </a>
            <a href="/pilier-prevention.php" class="pilier-nav-link pilier-nav-link--next">
                Pilier suivant : Prévention &amp; Éducation
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </nav>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>