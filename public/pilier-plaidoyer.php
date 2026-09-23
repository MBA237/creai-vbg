<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Pilier 5 — Plaidoyer politique & Partenariats';
$pageCss   = 'pilier-detail.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="pilier-detail-page">

    <section class="pilier-detail-hero pilier-detail-hero--purple">
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
                <span>Plaidoyer</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 11l18-8-8 18-2-8-8-2z"/>
                </svg>
                Pilier 5
            </span>

            <h1>Plaidoyer politique &amp; Partenariats</h1>

            <p class="pilier-detail-lead">
                Construire des partenariats institutionnels pour ancrer durablement
                une réponse multisectorielle aux violences basées sur le genre.
            </p>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow eyebrow--purple">L'impact systémique</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Nous construisons des partenariats avec les <strong>acteurs
                institutionnels</strong>, les collectivités territoriales
                décentralisées, les organisations de la société civile, les
                <strong>agences des Nations Unies</strong> et les partenaires
                techniques et financiers.
            </p>

            <p class="pilier-detail-text">
                Notre ambition : contribuer directement à l'<strong>élaboration</strong>
                et à l'<strong>évaluation</strong> des politiques publiques de
                lutte contre les VBG.
            </p>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Nos axes de plaidoyer</span>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18"/>
                            <path d="M5 21V10l7-5 7 5v11"/>
                            <path d="M9 21v-6h6v6"/>
                        </svg>
                    </div>
                    <h3>Plaidoyer institutionnel</h3>
                    <p>
                        Contribuer à l'élaboration, à l'amendement et à l'évaluation
                        des politiques publiques de lutte contre les VBG au Cameroun.
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
                    <h3>Partenariats stratégiques</h3>
                    <p>
                        Tisser des liens durables avec les institutions, les
                        collectivités et la société civile pour une réponse
                        coordonnée et efficace.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                    <h3>Coopération internationale</h3>
                    <p>
                        Collaboration avec les agences des Nations Unies, les
                        bailleurs internationaux et les organisations
                        panafricaines.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 11l18-8-8 18-2-8-8-2z"/>
                        </svg>
                    </div>
                    <h3>Mobilisation citoyenne</h3>
                    <p>
                        Sensibiliser les décideurs et l'opinion publique pour faire
                        évoluer les mentalités et les cadres légaux.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <div class="highlight-box highlight-box--purple">
                <h3>Une réponse multisectorielle et coordonnée</h3>
                <p>
                    Les VBG ne peuvent être combattues efficacement par un seul
                    acteur. Notre rôle est de <strong>fédérer</strong> les énergies
                    autour d'une stratégie commune, coordonnée et durable.
                </p>
            </div>
        </div>
    </section>

    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Construire un partenariat avec le CREAI-VBG</h2>
            <p>
                Institution, collectivité, ONG, entreprise ou agence internationale :
                engageons ensemble une collaboration structurante.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/contact.php" class="btn btn--primary">Proposer un partenariat</a>
                <a href="/piliers.php" class="btn btn--outline">Voir tous nos piliers</a>
            </div>
        </div>
    </section>

    <div class="pilier-detail-container">
        <nav class="pilier-nav-links">
            <a href="/pilier-accompagnement.php" class="pilier-nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Pilier précédent : Accompagnement
            </a>
            <a href="/piliers.php" class="pilier-nav-link pilier-nav-link--next">
                Retour aux piliers
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </nav>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>