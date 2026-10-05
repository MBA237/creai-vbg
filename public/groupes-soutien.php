<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Groupes de soutien — CREAI-VBG';
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
                <a href="/pilier-accompagnement.php">Accompagnement</a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <span>Groupes de soutien</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                </svg>
                Services offerts aux victimes
            </span>

            <h1>Groupes de soutien</h1>

            <p class="pilier-detail-lead">
                Se reconstruire, aux côtés d'autres survivant(e)s qui comprennent
                ce que vous traversez.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow eyebrow--purple">Plusieurs formules, un même esprit</span>

                    <p class="intro-split-lead">
                        Dans un <strong>cadre bienveillant et confidentiel</strong>, chaque personne partage son expérience et se reconstruit aux côtés d'autres survivant(e)s. Aucune démarche individuelle préalable n'est nécessaire.
                    </p>

                    <div class="intro-split-actions">
                        <a href="#calendrier" class="btn btn--primary">Voir le calendrier <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                        <a href="/solen.php" class="btn btn--outline">Découvrir Solen</a>
                    </div>
                </div>

                <aside class="fact-card" aria-label="En un coup d'œil">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                            <div>
                                <h3>Entre survivant(e)s</h3>
                                <p>Se réunir avec des personnes qui ont vécu une situation semblable à la vôtre.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                            <div>
                                <h3>Bienveillant et confidentiel</h3>
                                <p>Pair-aidance et reconnaissance entre pairs.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
                            <div>
                                <h3>Sans démarche préalable</h3>
                                <p>Aucune démarche individuelle n'est nécessaire pour participer.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Ce que vous y trouverez</span>

            <div class="programmes-grid">
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3>Partage d'expérience</h3>
                    <p>Se réunir avec des personnes qui ont vécu une situation de violence semblable à la vôtre.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 2v4"/>
                            <path d="M12 18v4"/>
                            <path d="M4.93 4.93l2.83 2.83"/>
                            <path d="M16.24 16.24l2.83 2.83"/>
                        </svg>
                    </div>
                    <h3>Reconnaissance entre pairs</h3>
                    <p>Découvrir que d'autres vivent les mêmes émotions et pensent comme vous.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        </svg>
                    </div>
                    <h3>Effet miroir & pair-aidance</h3>
                    <p>La force du groupe : réciprocité, validation collective et pair-aidance.</p>
                </article>
            </div>

            <!-- Calendrier : mis en évidence, avec un accès direct à la plateforme -->
            <div class="info-highlight info-highlight--spaced" id="calendrier">
                <div class="info-highlight-row">
                    <div class="info-highlight-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <span class="duo-card-tag">À venir</span>
                        <h3>Calendrier des groupes de soutien</h3>
                        <p>
                            Le calendrier des prochains groupes sera bientôt
                            disponible. En attendant, découvrez le Centre de
                            prévention des violences basées sur le genre.
                        </p>
                        <a href="https://vbg.colibri-cric.org/centre-de-prevention-des-violences-basees-sur-le-genre-cpvbg/" class="btn btn--primary" target="_blank" rel="noopener noreferrer">
                            En savoir plus
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M7 17L17 7"/>
                                <path d="M7 7h10v10"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         SOLEN — carte liée
         ============================================================ -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow eyebrow--purple">Envie d'une présence continue ?</span>

            <article class="programme-card programme-card--solen">
                <div class="programme-icon programme-icon--purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                    </svg>
                </div>
                <h3>Solen — communauté de soutien</h3>
                <p>Une communauté d'entraide entre survivant(e)s, accessible 24h/24 et 7j/7.</p>
                <a href="/solen.php" class="programme-link programme-link--purple">
                    Découvrir Solen
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </article>
        </div>
    </section>

    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Besoin d'aide ou d'information ?</h2>
            <p>
                Nos équipes vous accueillent du lundi au vendredi, de 9h00 à
                17h00, en toute confidentialité.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/besoin-aide.php" class="btn btn--primary">Demander de l'aide</a>
                <a href="/pilier-accompagnement.php" class="btn btn--outline">Retour à l'accompagnement</a>
            </div>
        </div>
    </section>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
