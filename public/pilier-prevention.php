<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Pilier 2 — Prévention & Éducation Communautaire';
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
                <span>Prévention</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                    <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
                Pilier 2
            </span>

            <h1>Prévention &amp; Éducation Communautaire</h1>

            <p class="pilier-detail-lead">
                Prévenir la violence avant qu'elle ne survienne : la transformation
                des mentalités passe par l'éducation, la sensibilisation et la
                formation continue.
            </p>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow">Prévenir la violence avant qu'elle ne survienne</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Pour lutter efficacement pour une société sans violence sexiste,
                nous avons besoin d'un <strong>changement de paradigme</strong> —
                un changement qui nous permette d'envisager une réalité où il est
                possible de prévenir la violence avant qu'elle ne se produise.
            </p>

            <p class="pilier-detail-text">
                Nous privilégions délibérément la <strong>prévention primaire</strong>,
                c'est-à-dire empêcher les préjudices avant même qu'ils ne surviennent,
                en apprenant aux individus comment éviter de nuire, plutôt que
                comment éviter d'en être victimes.
            </p>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow">Nos programmes de prévention</span>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="4" cy="18" r="1.5"/>
                            <path d="M4 16.5c0-5 5-4 7-8s-1-6 4-7"/>
                            <circle cx="18" cy="3" r="1.5"/>
                        </svg>
                    </div>
                    <h3>École Itinérante de Prévention des VBG</h3>
                    <p>
                        Actions de prévention grand public, en entreprises et auprès
                        de la jeunesse, pour promouvoir l'égalité entre les femmes
                        et les hommes, les filles et les garçons.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 11l18-8-8 18-2-8-8-2z"/>
                        </svg>
                    </div>
                    <h3>Campagnes de sensibilisation</h3>
                    <p>
                        Campagnes nationales avec supports variés : brochures, quiz,
                        flyers, capsules vidéo, dossiers pédagogiques, événements
                        grand public.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <h3>Centre de formation</h3>
                    <p>
                        Ateliers sur mesure pour les organismes et entreprises :
                        violence entre partenaires intimes, violence sexuelle,
                        prévention en milieu de travail.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="14" rx="2"/>
                            <path d="M8 21h8M12 17v4"/>
                        </svg>
                    </div>
                    <h3>Formations en ligne</h3>
                    <p>
                        Cours gratuits pour les professionnels de la police, de la
                        santé, du social, du juridique et de l'éducation, ainsi que
                        pour toute personne intéressée.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <div class="highlight-box">
                <h3>Un changement de paradigme nécessaire</h3>
                <p>
                    La prévention primaire nous invite à <strong>agir en amont</strong> :
                    éduquer les jeunes générations, transformer les mentalités,
                    déconstruire les stéréotypes de genre et créer les conditions
                    d'une société égalitaire.
                </p>
            </div>
        </div>
    </section>

    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Participer à nos programmes de prévention</h2>
            <p>
                Établissement scolaire, entreprise, institution ou association :
                nous concevons des interventions sur mesure adaptées à votre public.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/contact.php" class="btn btn--primary">Demander une intervention</a>
                <a href="/piliers.php" class="btn btn--outline">Voir tous nos piliers</a>
            </div>
        </div>
    </section>

    <div class="pilier-detail-container">
        <nav class="pilier-nav-links">
            <a href="/pilier-recherche.php" class="pilier-nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Pilier précédent : Recherche
            </a>
            <a href="/pilier-innovation.php" class="pilier-nav-link pilier-nav-link--next">
                Pilier suivant : Innovation
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </nav>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>