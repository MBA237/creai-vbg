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

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container">

            <div class="pilier-feature">

                <div class="pilier-feature-image">
                    <img src="/images/prevention.jpg" alt="Prévention et éducation communautaire contre les violences basées sur le genre" loading="lazy">
                </div>

                <div class="pilier-feature-content">
                    <span class="duo-card-tag">Prévention primaire</span>
                    <h2>Prévenir la violence avant qu'elle ne survienne</h2>

                    <p class="pilier-detail-text pilier-detail-text--lead">
                        Pour lutter efficacement pour une société sans violence
                        sexiste, nous avons besoin d'un
                        <strong>changement de paradigme</strong> — un changement qui
                        nous permette d'envisager une réalité où il est possible de
                        prévenir la violence avant qu'elle ne se produise.
                    </p>

                    <p class="pilier-detail-text">
                        Nous privilégions délibérément la
                        <strong>prévention primaire</strong>, c'est-à-dire empêcher
                        les préjudices avant même qu'ils ne surviennent, en
                        apprenant aux individus <strong>comment éviter de nuire</strong>,
                        plutôt que comment éviter d'en être victimes.
                    </p>

                    <div class="intro-split-actions" style="margin-top: 26px;">
                        <a href="#programmes" class="btn btn--primary">
                            Découvrir nos programmes
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <a href="/contact.php" class="btn btn--outline">Demander une intervention</a>
                    </div>
                </div>

            </div>

            <!-- Notre approche en trois idées -->
            <h3 class="tile-grid-title">Notre approche en trois idées</h3>
            <div class="tile-grid">
                <div class="tile">
                    <span class="tile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </span>
                    Prévention primaire
                </div>
                <div class="tile">
                    <span class="tile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </span>
                    Agir avant que la violence ne se produise
                </div>
                <div class="tile">
                    <span class="tile-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </span>
                    Apprendre à éviter de nuire
                </div>
            </div>

        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt" id="programmes">
        <div class="pilier-detail-container">
            <span class="eyebrow">Nos programmes de prévention</span>

            <p class="pilier-detail-text pilier-detail-text--lead" style="max-width: 68ch; margin-bottom: 32px;">
                <strong>Quatre leviers complémentaires</strong> : sensibiliser,
                éduquer et former, du grand public aux professionnel(le)s.
            </p>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="4" cy="18" r="1.5"/>
                            <path d="M4 16.5c0-5 5-4 7-8s-1-6 4-7"/>
                            <circle cx="18" cy="3" r="1.5"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Grand public, entreprises, jeunesse</span>
                    <h3>École Itinérante de Prévention des VBG</h3>
                    <p>
                        Actions de prévention grand public, en entreprises et auprès
                        de la jeunesse, pour promouvoir l'égalité entre les femmes
                        et les hommes, les filles et les garçons.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 11l18-8-8 18-2-8-8-2z"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Campagnes nationales</span>
                    <h3>Campagnes de sensibilisation</h3>
                    <p>
                        Campagnes nationales avec supports variés : brochures, quiz,
                        flyers, capsules vidéo, dossiers pédagogiques, événements
                        grand public.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Ateliers sur mesure</span>
                    <h3>Centre de formation</h3>
                    <p>
                        Ateliers sur mesure pour les organismes et entreprises :
                        violence entre partenaires intimes, violence sexuelle,
                        prévention en milieu de travail.
                    </p>
                    <a href="/contact.php" class="programme-link programme-link--purple">
                        Demander un atelier
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="14" rx="2"/>
                            <path d="M8 21h8M12 17v4"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Cours gratuits</span>
                    <h3>Formations en ligne</h3>
                    <p>
                        Cours gratuits pour les professionnels de la police, de la
                        santé, du social, du juridique et de l'éducation, ainsi que
                        pour toute personne intéressée.
                    </p>
                    <a href="/apprentissage.php" class="programme-link programme-link--purple">
                        Accéder au centre d'apprentissage
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <h3>Un changement de paradigme nécessaire</h3>
                        <p>
                            La prévention primaire nous invite à
                            <strong>agir en amont</strong> : éduquer les
                            jeunes générations, transformer les mentalités,
                            déconstruire les stéréotypes de genre et créer les
                            conditions d'une société égalitaire.
                        </p>
                    </div>
                </div>
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
                <span class="pilier-nav-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </span>
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Pilier précédent</span>
                    <span class="pilier-nav-title">Recherche</span>
                </span>
            </a>
            <a href="/pilier-innovation.php" class="pilier-nav-link pilier-nav-link--next">
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Pilier suivant</span>
                    <span class="pilier-nav-title">Innovation</span>
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