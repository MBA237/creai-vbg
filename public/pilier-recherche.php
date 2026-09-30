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
        <div class="pilier-detail-container">
            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow">Éclairer par la science</span>

                    <p class="intro-split-lead">
                        Nous produisons des <strong>connaissances fondamentales
                        et interdisciplinaires</strong> sur les causes, les
                        contextes et les réponses les plus efficaces à la
                        violence.
                    </p>

                    <div class="intro-split-actions">
                        <a href="#axes" class="btn btn--primary">
                            Découvrir nos axes de recherche
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <a href="/contact.php" class="btn btn--outline">Collaborer avec nous</a>
                    </div>
                </div>

                <aside class="fact-card" aria-label="La recherche au CREAI-VBG">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"/>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Notre conviction</h3>
                                <p>On ne combat efficacement que ce que l'on comprend précisément : ce pilier fonde la crédibilité de tout ce que nous entreprenons.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </span>
                            <div>
                                <h3>De la recherche à l'action</h3>
                                <p>Nos recherches sont intégrées dans nos solutions innovantes et notre renforcement des capacités.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="17 1 21 5 17 9"/>
                                    <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                                    <polyline points="7 23 3 19 7 15"/>
                                    <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Du terrain à la recherche</h3>
                                <p>Les enseignements de nos programmes éducatifs orientent à leur tour nos initiatives de recherche.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Ouverte aux partenariats</h3>
                                <p>Chercheur(e)s, universitaires, institutions et société civile : collaborons.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </section>

    <!-- GBV-RP -->
    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <div class="pilier-feature">

                <div class="pilier-feature-image">
                    <img src="/images/recherche.jpg" alt="Recherche sur les violences basées sur le genre au Cameroun" loading="lazy">
                </div>

                <div class="pilier-feature-content">
                    <span class="duo-card-tag">Programme phare</span>
                    <h2>Gender-Based Violence Research Program in Cameroon</h2>

                    <p class="pilier-detail-text pilier-detail-text--lead">
                        Le Gender-Based Violence Research Program in Cameroon
                        (GBV-RP), mis en place en collaboration avec
                        l'Institute for Gender Equality and Disability (IGED)
                        du CRIC, vise à mener des recherches sur la nature et
                        la prévention de la violence sexiste au Cameroun.
                    </p>

                    <p class="pilier-detail-text">
                        Nous étudions la nature, les causes et les
                        conséquences sur la santé physique et mentale des
                        victimes et de leurs proches, et dans la société
                        globale. Nous examinons également quelles
                        interventions sont efficaces, pour qui et dans
                        quelles circonstances. En collaboration avec des
                        universitaires, des décideurs politiques et des
                        partenaires de la société civile, nous traduisons
                        ces connaissances en outils concrets pour des
                        interventions, des soins et des politiques efficaces.
                    </p>

                    <a href="https://gbv-rp.colibri-cric.org/" class="btn btn--primary" target="_blank" rel="noopener noreferrer">
                        Découvrir le GBV-RP
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 17L17 7"/>
                            <path d="M7 7h10v10"/>
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- AXES DE RECHERCHE -->
    <section class="pilier-detail-section" id="axes">
        <div class="pilier-detail-container">
            <span class="eyebrow">Thèmes de recherche actuels</span>

            <p class="pilier-detail-text pilier-detail-text--lead" style="max-width: 68ch; margin-bottom: 32px;">
                Nos recherches s'organisent autour de <strong>cinq thèmes
                principaux</strong>.
            </p>

            <div class="grid-3-2">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Axe 1</span>
                    <h3>Comprendre, nommer et agir face aux féminicides</h3>
                    <p>
                        Cet axe a pour objectif de développer la recherche et
                        les connaissances pour pallier à ces lacunes.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Axe 2</span>
                    <h3>Violence entre partenaires intimes et détresse relationnelle</h3>
                    <p>
                        Cet axe vise à comprendre, prévenir et réduire la
                        violence entre partenaires intimes (VPI) ainsi que la
                        détresse relationnelle.
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
                    <span class="duo-card-tag">Axe 3</span>
                    <h3>Violences faites aux enfants</h3>
                    <p>
                        Cet axe vise à produire des connaissances permettant
                        de mieux connaître et comprendre les violences vécues
                        par les enfants ainsi que leurs conséquences, afin
                        d'éclairer l'action publique et de nourrir les
                        réflexions sur les réponses institutionnelles qui
                        peuvent être apportées.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <path d="M8 21h8"/>
                            <path d="M12 17v4"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Axe 4</span>
                    <h3>La violence numérique fondée sur le genre</h3>
                    <p>
                        Cet axe a pour objectif de construire un espace de
                        réflexion autour de la violence numérique de genre.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Axe 5</span>
                    <h3>Culture du viol et mythes associés</h3>
                    <p>
                        Cet axe vise à analyser les mythes et la culture du
                        viol au sein de la société camerounaise, en examinant
                        le développement des identités sociales dans la
                        création et le maintien de cette culture.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <!-- MISE EN AVANT -->
    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <div class="info-highlight">
                <div class="info-highlight-row">
                    <div class="info-highlight-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <h3>Un ancrage scientifique au service de l'action</h3>
                        <p>
                            Chaque intervention du CREAI-VBG s'appuie sur des
                            données probantes. Nos recherches ne restent pas
                            dans les tiroirs : elles nourrissent directement
                            nos programmes de prévention, nos outils
                            numériques et nos actions de terrain.
                        </p>
                    </div>
                </div>
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
                <span class="pilier-nav-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </span>
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Retour</span>
                    <span class="pilier-nav-title">Tous nos piliers</span>
                </span>
            </a>
            <a href="/pilier-prevention.php" class="pilier-nav-link pilier-nav-link--next">
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Pilier suivant</span>
                    <span class="pilier-nav-title">Prévention &amp; Éducation</span>
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