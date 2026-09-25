<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Pilier 4 — Accompagnement holistique';
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
                <span>Accompagnement</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Pilier 4
            </span>

            <h1>Accompagnement holistique</h1>

            <p class="pilier-detail-lead">
                L'action de terrain, pour les survivant(e)s comme pour les
                auteur(e)s : une prise en charge complète pour briser durablement
                le cycle de la violence.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow eyebrow--purple">Une approche à 360°</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Nous assurons la prise en charge psychosociale, médicale, juridique
                et économique des survivant(e)s, ainsi que leur orientation vers les
                structures compétentes.
            </p>

            <p class="pilier-detail-text">
                En parallèle — et c'est ce qui distingue notre approche — nous mettons
                en œuvre des <strong>parcours de responsabilisation</strong> destinés
                aux auteur(e)s de VBG, dans une logique assumée de prévention de la
                récidive.
            </p>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Services offerts aux victimes</span>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <h3>Ligne d'écoute téléphonique</h3>
                    <p>
                        Du lundi au vendredi, de 9h00 à 17h00. Écoute, information
                        et orientation en toute confidentialité.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3>Accompagnement psycho-social</h3>
                    <p>
                        Soutien psychologique individuel et collectif pour comprendre
                        les mécanismes de l'emprise et reconstruire son autonomie.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v18"/>
                            <path d="M5 8h14"/>
                            <path d="M5 8l-2 7a3 3 0 0 0 6 0l-2-7"/>
                            <path d="M19 8l-2 7a3 3 0 0 0 6 0l-2-7"/>
                        </svg>
                    </div>
                    <h3>Soutien juridique</h3>
                    <p>
                        Information sur vos droits, préparation d'un dépôt de plainte,
                        accompagnement tout au long de la procédure judiciaire.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <h3>Accompagnement au procès</h3>
                    <p>
                        Préparation avant l'audience, présence physique pendant le
                        procès et suivi après l'audience.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        </svg>
                    </div>
                    <h3>Groupes de soutien</h3>
                    <p>
                        Pair-aidance et reconnaissance entre pairs, dans un cadre
                        bienveillant et confidentiel.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <h3>Orientation vers les partenaires</h3>
                    <p>
                        Mise en relation avec les structures médicales, juridiques
                        et sociales de votre région.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Services offerts aux auteur(e)s</span>

            <div class="pilier-detail-container pilier-detail-container--narrow" style="padding: 0;">
                <p class="pilier-detail-text">
                    Le CREAI-VBG développe un programme d'accueil et d'accompagnement
                    des auteur(e)s de violences, engagé(e)s dans une démarche
                    judiciaire ou volontaire. Ce programme concourt à prévenir le
                    passage à l'acte et la récidive.
                </p>
            </div>

            <div class="programmes-grid" style="margin-top: 32px;">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h3>Accompagnement collectif</h3>
                    <p>
                        Groupe de parole et stage de responsabilisation pour
                        favoriser l'expression des difficultés et prévenir les
                        conduites violentes.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <h3>Accompagnement individuel</h3>
                    <p>
                        Entretien thérapeutique, accompagnement médical, social
                        et professionnel adapté à chaque situation.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="6"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <h3>Objectifs du parcours</h3>
                    <p>
                        Faire comprendre les mécanismes de la violence, amener à
                        prendre la mesure des conséquences, transmettre le principe
                        d'égalité.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <div class="highlight-box highlight-box--purple">
                <h3>Ne restez pas seul(e)</h3>
                <p>
                    Notre expertise en violences basées sur le genre peut vous aider
                    à sortir des violences. Chaque situation est unique : une écoute
                    peut tenir compte de la complexité de votre situation et donner
                    des réponses à vos besoins spécifiques.
                </p>
            </div>
        </div>
    </section>

    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Besoin d'aide ou d'information ?</h2>
            <p>
                Nos équipes vous accueillent du lundi au vendredi, de 9h00 à 17h00,
                en toute confidentialité.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/besoin-aide.php" class="btn btn--primary">Demander de l'aide</a>
                <a href="/piliers.php" class="btn btn--outline">Voir tous nos piliers</a>
            </div>
        </div>
    </section>

    <div class="pilier-detail-container">
        <nav class="pilier-nav-links">
            <a href="/pilier-innovation.php" class="pilier-nav-link">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
                Pilier précédent : Innovation
            </a>
            <a href="/pilier-plaidoyer.php" class="pilier-nav-link pilier-nav-link--next">
                Pilier suivant : Plaidoyer
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </a>
        </nav>
    </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>