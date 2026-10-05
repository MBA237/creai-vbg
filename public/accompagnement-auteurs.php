<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Accueil et responsabilisation des auteur(e)s — CREAI-VBG';
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
                <span>Auteur(e)s de violences</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <circle cx="12" cy="12" r="6"/>
                    <circle cx="12" cy="12" r="2"/>
                </svg>
                Services offerts aux auteur(e)s
            </span>

            <h1>Accueil et responsabilisation des auteur(e)s de violences</h1>

            <p class="pilier-detail-lead">
                Prévenir le passage à l'acte et la récidive, pour mieux protéger
                les victimes.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow eyebrow--purple">Un parcours de responsabilisation</span>

                    <p class="intro-split-lead">
                        Pour les auteur(e)s de violences au sein du couple, engagé(e)s dans une démarche judiciaire ou volontaire : <strong>un parcours global pour mieux protéger les victimes</strong>.
                    </p>

                    <div class="intro-split-actions">
                        <a href="/contact.php" class="btn btn--primary">Nous contacter <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
                        <a href="#volets" class="btn btn--outline">Voir les deux volets</a>
                    </div>
                </div>

                <aside class="fact-card" aria-label="En un coup d'œil">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></span>
                            <div>
                                <h3>Prévenir le passage à l'acte</h3>
                                <p>Un programme qui concourt à prévenir la récidive.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                            <div>
                                <h3>Collectif et individuel</h3>
                                <p>Groupe de parole, stage, entretien thérapeutique, suivi médical et social.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span>
                            <div>
                                <h3>Volontaire ou judiciaire</h3>
                                <p>Ouvert à toute personne venant de manière volontaire ou sur orientation judiciaire.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
                            <div>
                                <h3>Lancement à venir</h3>
                                <p>Ce programme sera bientôt proposé.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </section>

    <!-- ============================================================
         LES DEUX VOLETS D'ACCOMPAGNEMENT
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt" id="volets">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Deux volets d'accompagnement</span>

            <div class="duo-grid">
                <div class="duo-card" id="accompagnement-collectif">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                    </div>
                    <span class="duo-card-tag">Collectif</span>
                    <h3>Accompagnement collectif</h3>
                    <div class="tag-list tag-list--check">
                        <span class="tag">Groupe de parole</span>
                        <span class="tag">Stage de responsabilisation</span>
                    </div>
                </div>
                <div class="duo-card" id="accompagnement-individuel">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <span class="duo-card-tag">Individuel</span>
                    <h3>Accompagnement individuel</h3>
                    <div class="tag-list tag-list--check">
                        <span class="tag">Entretien thérapeutique</span>
                        <span class="tag">Accompagnement médical</span>
                        <span class="tag">Accompagnement social et professionnel</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         OBJECTIFS DU PARCOURS
         ============================================================ -->
    <section class="pilier-detail-section" id="objectifs-parcours">
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow eyebrow--purple">Objectifs du parcours</span>

            <div class="tile-grid">
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-1.04z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-1.04z"/></svg></span>
                                Comprendre les mécanismes de la violence
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M5 8h14"/><path d="M5 8l-2 7a3 3 0 0 0 6 0l-2-7"/><path d="M19 8l-2 7a3 3 0 0 0 6 0l-2-7"/></svg></span>
                                Mesurer les conséquences judiciaires
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></span>
                                Se responsabiliser, changer
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                                Égalité femmes-hommes
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
                                Nouvelles conduites relationnelles
                            </div>
                            <div class="tile">
                                <span class="tile-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span>
                                Exprimer ses difficultés
                            </div>
            </div>

            <div class="info-highlight info-highlight--spaced">
                <div class="info-highlight-row">
                    <div class="info-highlight-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="6"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <h3>Public visé</h3>
                        <p>Toute personne venant de manière volontaire ou sur orientation judiciaire.</p>
                    </div>
                </div>
            </div>

            <p class="pilier-detail-text pilier-detail-note">
                Lancement de ce programme à venir.
            </p>
        </div>
    </section>

    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Une question sur ce programme ?</h2>
            <p>
                Notre équipe est à votre disposition pour répondre à vos
                interrogations, en toute confidentialité.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/contact.php" class="btn btn--primary">Nous contacter</a>
                <a href="/pilier-accompagnement.php" class="btn btn--outline">Retour à l'accompagnement</a>
            </div>
        </div>
    </section>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
