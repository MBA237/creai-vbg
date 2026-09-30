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
        <div class="pilier-detail-container pilier-detail-container--narrow">
            <span class="eyebrow eyebrow--purple">Un parcours de responsabilisation</span>

            <p class="pilier-detail-text pilier-detail-text--lead">
                Pour les auteur(e)s de violences au sein du couple, engagé(e)s
                dans une démarche judiciaire ou volontaire : un parcours global
                pour mieux protéger les victimes.
            </p>
        </div>
    </section>

    <!-- ============================================================
         LES DEUX VOLETS D'ACCOMPAGNEMENT
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Deux volets d'accompagnement</span>

            <div class="duo-grid">
                <div class="duo-card" id="accompagnement-collectif">
                    <span class="duo-card-tag">Collectif</span>
                    <h3>Accompagnement collectif</h3>
                    <div class="tag-list" style="margin-top: 8px;">
                        <span class="tag">Groupe de parole</span>
                        <span class="tag">Stage de responsabilisation</span>
                    </div>
                </div>
                <div class="duo-card" id="accompagnement-individuel">
                    <span class="duo-card-tag">Individuel</span>
                    <h3>Accompagnement individuel</h3>
                    <div class="tag-list" style="margin-top: 8px;">
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

            <div class="tag-list">
                <span class="tag">Comprendre les mécanismes de la violence</span>
                <span class="tag">Mesurer les conséquences judiciaires</span>
                <span class="tag">Se responsabiliser, changer</span>
                <span class="tag">Égalité femmes-hommes</span>
                <span class="tag">Nouvelles conduites relationnelles</span>
                <span class="tag">Exprimer ses difficultés</span>
            </div>

            <div class="info-highlight" style="margin-top: 28px;">
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

            <p class="pilier-detail-text" style="margin-top: 16px; font-size: 13.5px; color: var(--text-muted);">
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
