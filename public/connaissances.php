<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Centre de connaissances — CREAI-VBG';
$pageCss   = 'connaissances.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="page-content">

<!-- ============================================================
     HERO
     ============================================================ -->
<section class="page-hero">
    <div class="page-hero-content">
        <span class="page-badge">Centre de connaissances</span>
        <h1>Des ressources fondées sur des données probantes</h1>
        <p class="page-lead">
            Découvrez une sélection d'outils, de guides, d'études de cas et autres
            ressources fondées sur des données probantes pour renforcer votre travail
            de prévention et de réponse à la violence sexiste.
        </p>

        <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
    </div>
</section>

<!-- ============================================================
     PRENDRE SOIN DE SOI
     ============================================================ -->
<section class="page-section page-section--alt kc-notice-section">
    <div class="page-container">
        <div class="callout callout--care" role="note">
            <div class="callout-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
            </div>
            <div>
                <h3>Prenez soin de vous</h3>
                <p>
                    Certains contenus auxquels vous pourriez accéder pourraient être
                    bouleversants ou éprouvants émotionnellement. Nous vous encourageons
                    à prendre soin de vous et à faire une pause si nécessaire.
                </p>
                <p>
                    Si vous êtes vous-même concerné(e) par une situation de violence,
                    <a href="/besoin-aide.php">des ressources d'aide sont à votre disposition</a>.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CE QUE VOUS Y TROUVEREZ
     ============================================================ -->
<section class="page-section">
    <div class="page-container">
        <h2 class="section-title">Ce que vous y trouverez</h2>
        <p class="section-intro">
            Des ressources sont disponibles
            <span class="lang-tag">Français</span> et <span class="lang-tag">English</span>
            pour soutenir les praticiens dans tout le Cameroun et au-delà.
        </p>

        <div class="cards-grid cards-grid--4">

            <article class="info-card info-card--teal">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                </div>
                <h3>Guides</h3>
                <p>Des guides pratiques pour accompagner vos actions de prévention et de réponse.</p>
            </article>

            <article class="info-card info-card--purple">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <h3>Outils</h3>
                <p>Des outils à utiliser sur le terrain, dans vos organisations et vos communautés.</p>
            </article>

            <article class="info-card info-card--coral">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <h3>Études de cas</h3>
                <p>Des situations analysées pour tirer les enseignements de l'expérience.</p>
            </article>

            <article class="info-card info-card--gray">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                </div>
                <h3>Données probantes</h3>
                <p>Des ressources fondées sur la recherche et sur des données fiables.</p>
            </article>

        </div>
    </div>
</section>

<!-- ============================================================
     BIBLIOTHÈQUE EN COURS DE CONSTITUTION
     ============================================================ -->
<section class="page-section page-section--alt">
    <div class="page-container">
        <div class="notice-soon">
            <h3>La bibliothèque est en cours de constitution</h3>
            <p>
                Les premières ressources seront publiées prochainement.
                En attendant, suivez nos actualités ou écrivez-nous pour être informé(e) de leur mise en ligne.
            </p>
            <div class="notice-actions">
                <a href="/actualites.php" class="btn-cta btn-cta--primary">Voir les actualités</a>
                <a href="/contact.php" class="btn-cta btn-cta--ghost">Nous écrire</a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="page-cta">
    <div class="page-container">
        <h2>Aller plus loin</h2>
        <p>
            Renforcez vos compétences avec notre Centre d'apprentissage :
            webinaires, cours gratuits et échanges entre pairs.
        </p>
        <div class="cta-buttons">
            <a href="/apprentissage.php" class="btn-cta btn-cta--primary">Centre d'apprentissage</a>
            <a href="/rejoindre.php" class="btn-cta btn-cta--ghost">Nous rejoindre</a>
        </div>
    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
