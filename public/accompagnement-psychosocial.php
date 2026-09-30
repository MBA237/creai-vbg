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

    <!-- ============================================================
         INTRO
         ============================================================ -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow eyebrow--purple">Un accompagnement spécialisé</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Une équipe pluridisciplinaire, formée aux réalités des VBG, vous
                aide à mobiliser vos propres ressources — individuellement ou en
                groupe. Dès le premier entretien, nous identifions ensemble vos
                besoins et le soutien le plus adapté.
            </p>

            <div class="duo-grid">
                <div class="duo-card">
                    <span class="duo-card-tag">Volet 1</span>
                    <h3>Soutien psychologique</h3>
                    <p>Comprendre l'emprise et reprendre du pouvoir sur sa vie.</p>
                </div>
                <div class="duo-card">
                    <span class="duo-card-tag">Volet 2</span>
                    <h3>Soutien juridique</h3>
                    <p>Préparer un dépôt de plainte et comprendre la procédure.</p>
                </div>
                <div class="duo-card">
                    <span class="duo-card-tag">Volet 3</span>
                    <h3>Accompagnement au procès</h3>
                    <p>Une présence à vos côtés, avant, pendant et après l'audience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         SOUTIEN PSYCHOLOGIQUE
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt" id="soutien-psychologique">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow eyebrow--purple">Un soutien psychologique</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Pour comprendre les mécanismes des violences sexistes et de
                l'emprise, et reprendre du pouvoir sur votre vie, à votre rythme.
            </p>

            <div class="duo-grid">
                <div class="duo-card">
                    <span class="duo-card-tag">Qu'est-ce que c'est ?</span>
                    <p>
                        Un espace d'écoute sécurisé, animé par un(e) psychologue
                        formé(e) au psychotraumatisme. Gratuit et confidentiel.
                    </p>
                    <div class="tag-list">
                        <span class="tag">Entretien individuel</span>
                        <span class="tag">Téléphone</span>
                        <span class="tag">WhatsApp</span>
                        <span class="tag">Groupe de parole</span>
                    </div>
                </div>
                <div class="duo-card">
                    <span class="duo-card-tag">Pour qui ?</span>
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
    <section class="pilier-detail-section" id="soutien-juridique">
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
    <section class="pilier-detail-section pilier-detail-section--alt" id="accompagnement-proces">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Accompagnement au procès</span>

            <p class="pilier-detail-text pilier-detail-text--lead" style="max-width: 68ch;">
                Un accompagnement moral et pédagogique, complémentaire de celui
                de l'avocat, pour toute victime — mais aussi tout proche ou
                témoin convoqué à l'audience.
            </p>

            <div class="programmes-grid" style="margin-top: 24px;">
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <h3>Avant l'audience</h3>
                    <p>Deux entretiens de préparation, visite de la salle, lien avec les auxiliaires de justice.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <h3>Pendant l'audience</h3>
                    <p>Présence physique et soutien tout au long du procès.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        </svg>
                    </div>
                    <h3>Après l'audience</h3>
                    <p>Entretien final et orientation vers un suivi judiciaire, médical ou social.</p>
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
