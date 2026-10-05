<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Accompagnement psycho-social — CREAI-VBG';
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
                <span>Accompagnement psycho-social</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                Services offerts aux victimes
            </span>

            <h1>Accompagnement psycho-social</h1>

            <p class="pilier-detail-lead">
                Comprendre les violences subies, briser l'emprise, reconstruire
                son autonomie — à votre rythme, en toute confidentialité.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow eyebrow--purple">Un accompagnement spécialisé</span>

                    <p class="intro-split-lead">
                        Une équipe <strong>pluridisciplinaire, formée aux réalités des VBG</strong>, vous aide à mobiliser vos propres ressources — individuellement ou en groupe. Dès le premier entretien, nous identifions ensemble vos besoins et le soutien le plus adapté.
                    </p>

                    <div class="intro-split-actions">
                        <a href="/besoin-aide.php" class="btn btn--primary">Demander de l'aide <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                        <a href="#volets" class="btn btn--outline">Découvrir les 3 volets</a>
                    </div>
                </div>

                <aside class="fact-card" aria-label="En un coup d'œil">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                            <div>
                                <h3>Individuel ou collectif</h3>
                                <p>Un soutien adapté à votre situation, à votre rythme.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>
                            <div>
                                <h3>Une équipe formée aux VBG</h3>
                                <p>Pluridisciplinaire, à l'écoute des réalités que vous vivez.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></span>
                            <div>
                                <h3>Dès le premier entretien</h3>
                                <p>Nous identifions ensemble vos besoins et le soutien le plus adapté.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                            <div>
                                <h3>Gratuit et confidentiel</h3>
                                <p>Un espace d'écoute sécurisé, animé par un(e) psychologue formé(e) au psychotraumatisme.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt" id="volets">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Trois volets complémentaires</span>

            <div class="duo-grid">
                <a class="duo-card duo-card--link" href="#soutien-psychologique">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </div>
                    <span class="duo-card-tag">Volet 1</span>
                    <h3>Soutien psychologique</h3>
                    <p>Comprendre l'emprise et reprendre du pouvoir sur sa vie.</p>
                    <span class="duo-card-more">Découvrir <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
                </a>
                <a class="duo-card duo-card--link" href="#soutien-juridique">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M5 8h14"/><path d="M5 8l-2 7a3 3 0 0 0 6 0l-2-7"/><path d="M19 8l-2 7a3 3 0 0 0 6 0l-2-7"/></svg>
                    </div>
                    <span class="duo-card-tag">Volet 2</span>
                    <h3>Soutien juridique</h3>
                    <p>Préparer un dépôt de plainte et comprendre la procédure.</p>
                    <span class="duo-card-more">Découvrir <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
                </a>
                <a class="duo-card duo-card--link" href="#accompagnement-proces">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                    </div>
                    <span class="duo-card-tag">Volet 3</span>
                    <h3>Accompagnement au procès</h3>
                    <p>Une présence à vos côtés, avant, pendant et après l'audience.</p>
                    <span class="duo-card-more">Découvrir <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         SOUTIEN PSYCHOLOGIQUE
         ============================================================ -->
    <section class="pilier-detail-section" id="soutien-psychologique">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Un soutien psychologique</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Pour comprendre les mécanismes des violences sexistes et de
                l'emprise, et reprendre du pouvoir sur votre vie, à votre rythme.
            </p>

            <div class="duo-grid duo-grid--feature">
                <div class="duo-card">
                    <div class="duo-card-head">
                        <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                        <span class="duo-card-tag">Qu'est-ce que c'est ?</span>
                    </div>
                    <p>
                        Un espace d'écoute sécurisé, animé par un(e) psychologue
                        formé(e) au psychotraumatisme. <strong>Gratuit et confidentiel.</strong>
                    </p>
                    <p class="duo-card-label">Formats proposés</p>
                    <div class="tile-grid tile-grid--2 tile-grid--compact">
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                                Entretien individuel
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                                Téléphone
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
                                WhatsApp
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                                Groupe de parole
                            </div>
                    </div>
                </div>
                <div class="duo-card">
                    <div class="duo-card-head">
                        <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                    </div>
                        <span class="duo-card-tag">Pour qui ?</span>
                    </div>
                    <p>
                        Toute personne victime de violence, avec ou sans dépôt de
                        plainte, ainsi que ses proches.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         SOUTIEN JURIDIQUE
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt" id="soutien-juridique">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Un soutien juridique</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Nos juristes vous informent sur vos droits tout au long de la
                procédure — mais ne vous représentent pas.
            </p>

            <div class="programmes-grid">
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <h3>Avant (pré-sentenciel)</h3>
                    <p>Dépôt de plainte, main courante, procédures possibles.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <h3>Durant la procédure</h3>
                    <p>Enquête, suites données à la plainte, droits, recours.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </div>
                    <h3>Après (post-sentenciel)</h3>
                    <p>Indemnisation et exécution de la peine.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- ============================================================
         ACCOMPAGNEMENT AU PROCÈS
         ============================================================ -->
    <section class="pilier-detail-section" id="accompagnement-proces">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Accompagnement au procès</span>

            <p class="pilier-detail-text pilier-detail-text--lead pilier-detail-text--wide">
                Un accompagnement moral et pédagogique, complémentaire de celui
                de l'avocat, pour toute victime — mais aussi tout proche ou
                témoin convoqué à l'audience.
            </p>

            <ol class="parcours">
                <li class="parcours-step">
                    <h3>Avant l'audience</h3>
                    <p>Deux entretiens de préparation, visite de la salle, lien avec les auxiliaires de justice.</p>
                </li>
                <li class="parcours-step">
                    <h3>Pendant l'audience</h3>
                    <p>Présence physique et soutien tout au long du procès.</p>
                </li>
                <li class="parcours-step">
                    <h3>Après l'audience</h3>
                    <p>Entretien final et orientation vers un suivi judiciaire, médical ou social.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <div class="info-highlight">
                <div class="info-highlight-row">
                    <div class="info-highlight-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <h3>Ne restez pas seul(e)</h3>
                        <p>
                            Notre expertise en violences basées sur le genre
                            peut vous aider à sortir des violences.
                            <strong>Vous n'êtes pas seules</strong> : chaque
                            appel est un échange confidentiel avec un(e)
                            professionnel(le) du CREAI-VBG.
                        </p>
                        <a href="/besoin-aide.php" class="btn btn--primary">Demander de l'aide</a>
                    </div>
                </div>
            </div>
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
