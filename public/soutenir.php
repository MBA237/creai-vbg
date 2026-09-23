<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Nous soutenir — CREAI-VBG';
$pageCss   = 'soutenir.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="page-content">

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="page-hero">
    <div class="page-hero-content">
        <span class="page-badge">Nous soutenir</span>
        <h1>Agissez à nos côtés contre les VBG</h1>
        <p class="page-lead">
            Notre action dépend de la mobilisation de ressources humaines,
            financières et techniques. Vous pouvez nous soutenir de plusieurs façons.
        </p>
    </div>
</section>

<!-- ============================================================
     4 FAÇONS DE SOUTENIR
     ============================================================ -->
<section class="page-section">
    <div class="page-container">
        <h2 class="section-title">Comment nous soutenir ?</h2>
        <p class="section-intro">Chacun peut contribuer, à la mesure de ses moyens et de son temps.</p>

        <div class="cards-grid cards-grid--2">

            <article class="info-card info-card--coral">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3>Faire un don</h3>
                <p>
                    Chaque contribution, quel que soit son montant, finance directement
                    nos programmes de recherche, de prévention et d'accompagnement.
                </p>
                <a href="/contact.php?sujet=don" class="info-card-link">Faire un don →</a>
            </article>

            <article class="info-card info-card--teal">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 17l2 2a1 1 0 1 0 3-3"/>
                        <path d="M14 14l2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/>
                        <path d="M21 3l1 11h-2"/>
                        <path d="M3 3L2 14l6.5 6.5a1 1 0 1 0 3-3"/>
                        <path d="M3 4h8"/>
                    </svg>
                </div>
                <h3>Devenir partenaire technique ou financier</h3>
                <p>
                    Institutions, entreprises et organisations sont invitées à nous
                    contacter pour construire des partenariats sur mesure.
                </p>
                <a href="/contact.php?sujet=partenariat" class="info-card-link">Devenir partenaire →</a>
            </article>

            <article class="info-card info-card--purple">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <h3>Donner de son temps</h3>
                <p>
                    Nos programmes reposent aussi sur l'engagement de bénévoles
                    formés à nos côtés.
                </p>
                <a href="/contact.php?sujet=benevolat" class="info-card-link">Proposer mon aide →</a>
            </article>

            <article class="info-card info-card--gray">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="18" cy="5" r="3"/>
                        <circle cx="6" cy="12" r="3"/>
                        <circle cx="18" cy="19" r="3"/>
                        <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                        <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                    </svg>
                </div>
                <h3>Relayer nos messages</h3>
                <p>
                    Suivre et partager nos contenus de sensibilisation contribue
                    directement à notre mission d'éducation.
                </p>
                <a href="/actualites.php" class="info-card-link">Voir nos actualités →</a>
            </article>

        </div>
    </div>
</section>

<!-- ============================================================
     TRANSPARENCE
     ============================================================ -->
<section class="page-section page-section--alt">
    <div class="page-container">
        <div class="callout">
            <div class="callout-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
            </div>
            <div>
                <h3>Une association à but strictement non lucratif</h3>
                <p>
                    L'ensemble de nos ressources est exclusivement affecté à la réalisation
                    de notre objet social. Aucune distribution de bénéfice n'est faite
                    à ses membres ou dirigeants.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="page-cta">
    <div class="page-container">
        <h2>Ensemble, rendons les VBG évitables</h2>
        <p>
            Faites un don ou associez votre organisation à nos actions :
            nous vous répondrons pour définir la meilleure façon de contribuer.
        </p>
        <div class="cta-buttons">
            <a href="/contact.php?sujet=don" class="btn-cta btn-cta--primary">Faire un don</a>
            <a href="/contact.php?sujet=partenariat" class="btn-cta btn-cta--ghost">Devenir partenaire</a>
        </div>
    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
