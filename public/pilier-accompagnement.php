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
        <div class="pilier-detail-container">
            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow eyebrow--purple">Une approche à 360°</span>

                    <p class="intro-split-lead">
                        Nous assurons la prise en charge <strong>psychosociale, médicale, juridique et économique</strong> des survivant(e)s, ainsi que leur orientation vers les structures compétentes.
                    </p>
                    <p class="pilier-detail-text pilier-detail-note-text">
                        En parallèle — et c'est ce qui distingue notre approche — nous mettons en œuvre des <strong>parcours de responsabilisation</strong> destinés aux auteur(e)s de VBG, dans une logique assumée de prévention de la récidive.
                    </p>

                    <div class="intro-split-actions">
                        <a href="/besoin-aide.php" class="btn btn--primary">Demander de l'aide <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                        <a href="#services" class="btn btn--outline">Voir nos services</a>
                    </div>
                </div>

                <aside class="fact-card" aria-label="En un coup d'œil">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-1.04z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-1.04z"/></svg></span>
                            <div>
                                <h3>Psychosocial</h3>
                                <p>Soutien psychologique individuel et collectif.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></span>
                            <div>
                                <h3>Médical</h3>
                                <p>Prise en charge médicale des survivant(e)s.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M5 8h14"/><path d="M5 8l-2 7a3 3 0 0 0 6 0l-2-7"/><path d="M19 8l-2 7a3 3 0 0 0 6 0l-2-7"/></svg></span>
                            <div>
                                <h3>Juridique</h3>
                                <p>Information, plainte et accompagnement au procès.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9.5a3 3 0 0 0-3-1.5c-1.7 0-3 1-3 2.3 0 3 6 1.7 6 4.7 0 1.3-1.3 2.5-3 2.5a3.2 3.2 0 0 1-3-1.8"/><line x1="12" y1="6" x2="12" y2="18"/></svg></span>
                            <div>
                                <h3>Économique</h3>
                                <p>Prise en charge économique des survivant(e)s.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </section>

    <section class="pilier-detail-section pilier-detail-section--alt" id="services">
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
                    <a href="/permanence-telephonique.php" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/accompagnement-psychosocial.php#soutien-psychologique" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/accompagnement-psychosocial.php#soutien-juridique" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/accompagnement-psychosocial.php#accompagnement-proces" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/groupes-soutien.php" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/permanence-telephonique.php#orientation" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </article>

                <article class="programme-card programme-card--solen">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </div>
                    <h3>Solen — communauté de soutien</h3>
                    <p>
                        Une communauté d'entraide entre ancien(ne)s et actuelles
                        survivant(e)s, accessible 24h/24 et 7j/7, où chacune veille
                        sur l'autre.
                    </p>
                    <a href="/solen.php" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/accompagnement-auteurs.php#accompagnement-collectif" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/accompagnement-auteurs.php#accompagnement-individuel" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
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
                    <a href="/accompagnement-auteurs.php#objectifs-parcours" class="programme-link programme-link--purple">
                        En savoir plus
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </article>

            </div>
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
                            Notre expertise en violences basées sur le genre peut
                            vous aider à sortir des violences.
                            <strong>Chaque situation est unique</strong> : une
                            écoute peut tenir compte de la complexité de votre
                            situation et donner des réponses à vos besoins
                            spécifiques.
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
                <span class="pilier-nav-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </span>
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Pilier précédent</span>
                    <span class="pilier-nav-title">Innovation</span>
                </span>
            </a>
            <a href="/pilier-plaidoyer.php" class="pilier-nav-link pilier-nav-link--next">
                <span class="pilier-nav-text">
                    <span class="pilier-nav-label">Pilier suivant</span>
                    <span class="pilier-nav-title">Plaidoyer</span>
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