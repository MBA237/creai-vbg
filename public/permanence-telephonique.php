<?php
declare(strict_types=1);
session_start();

$aideConfig = require __DIR__ . '/../config/aide.php';
$tel        = trim($aideConfig['ligne_ecoute']['telephone'] ?? '');
$telHref    = preg_replace('/[^\d+]/', '', $tel) ?? '';
// Tant que le numéro n'est pas renseigné dans config/aide.php, on ne montre jamais
// de faux numéro : on désigne la permanence par son nom.
$ligne      = $tel !== '' ? $tel : 'notre permanence téléphonique';

// Statut en direct de la permanence (Lundi-Vendredi, 9h-17h, heure du Cameroun)
// — un petit détail qui rend la disponibilité concrète plutôt que déclarative.
$horaireTz    = new DateTimeZone('Africa/Douala');
$horaireNow   = new DateTime('now', $horaireTz);
$horaireJour  = (int) $horaireNow->format('N');   // 1 (lundi) .. 7 (dimanche)
$horaireHeure = (int) $horaireNow->format('G');
$permanenceOuverte = $horaireJour >= 1 && $horaireJour <= 5 && $horaireHeure >= 9 && $horaireHeure < 17;

$pageTitle = 'Permanence téléphonique — CREAI-VBG';
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
                <span>Permanence téléphonique</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                Services offerts aux victimes
            </span>

            <h1>Permanence téléphonique</h1>

            <p class="pilier-detail-lead">
                Ligne d'écoute, d'information et d'orientation. La porte d'entrée
                de notre accompagnement.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <!-- ============================================================
         INTRODUCTION — texte + repères, puis avertissement d'urgence
         ============================================================ -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container">

            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow eyebrow--purple">Une porte d'entrée vers l'accompagnement</span>

                    <p class="intro-split-lead">
                        Nous <strong>écoutons, informons et orientons</strong> les
                        victimes de violences basées sur le genre, ainsi que leurs
                        proches et les professionnel(le)s qui les accompagnent.
                    </p>

                    <div class="intro-split-actions">
                        <?php if ($tel !== ''): ?>
                            <a href="tel:<?= htmlspecialchars($telHref) ?>" class="btn btn--primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                <?= htmlspecialchars($tel) ?>
                            </a>
                        <?php else: ?>
                            <a href="/besoin-aide.php" class="btn btn--primary">
                                Demander de l'aide
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        <a href="#offre" class="btn btn--outline">Découvrir nos services</a>
                    </div>
                </div>

                <aside class="fact-card" aria-label="La permanence en un coup d'œil">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Du lundi au vendredi, de 9h00 à 17h00</h3>
                                <p>
                                    <span class="availability-badge<?= $permanenceOuverte ? '' : ' availability-badge--closed' ?>" style="margin-bottom: 0;">
                                        <span class="availability-dot" aria-hidden="true"></span>
                                        <?= $permanenceOuverte ? 'Ouvert maintenant' : 'Fermé pour le moment' ?>
                                    </span>
                                </p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Ouverte à toutes et à tous</h3>
                                <p>Victimes, proches, professionnel(le)s : toute personne qui s'interroge sur une situation de violence.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Anonyme et confidentielle</h3>
                                <p>Une écoute ponctuelle où l'anonymat et la confidentialité sont garantis.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Sans jugement, à votre rythme</h3>
                                <p>Des écoutant(e)s formé(e)s à une écoute bienveillante, dans le respect de votre rythme.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>

            <!-- Avertissement : ce n'est pas une ligne d'urgence -->
            <div class="info-highlight info-highlight--spaced">
                <div class="info-highlight-row info-highlight-row--alert">
                    <div class="info-highlight-icon info-highlight-icon--alert" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <h3>Ce numéro n'est pas un numéro d'urgence</h3>
                        <p>
                            En danger immédiat&nbsp;? Appelez la police, la
                            gendarmerie, les sapeurs-pompiers ou le SAMU.
                        </p>
                        <a href="/besoin-aide.php#urgence" class="btn btn--outline">Voir les numéros d'urgence</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================================
         CE QUE NOUS VOUS OFFRONS
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt" id="offre">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Ce que nous vous offrons</span>

            <div class="grid-3-2">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3>Un accueil sans jugement</h3>
                    <p>Ouverte à toutes les femmes victimes de violences, quelle que soit leur situation.</p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <h3>Une écoute bienveillante</h3>
                    <p>Des écoutantes formées installent une relation de confiance, à votre rythme.</p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </div>
                    <h3>L'identification de vos besoins</h3>
                    <p>Nous vous aidons à mettre des mots sur votre situation et vos difficultés.</p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </div>
                    <h3>Une information adaptée</h3>
                    <p>Sur vos droits, les démarches et les dispositifs existants.</p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                        </svg>
                    </div>
                    <h3>Une orientation sur mesure</h3>
                    <p>Vers un premier rendez-vous chez nous, ou vers un partenaire adapté.</p>
                </article>

            </div>

            <div class="info-highlight info-highlight--spaced">
                <div class="info-highlight-row">
                    <div class="info-highlight-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <div class="info-highlight-text">
                        <h3>Vous n'êtes pas seules</h3>
                        <p>
                            Chaque appel est un échange confidentiel avec
                            un(e) professionnel(le) du CREAI-VBG.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         QUAND APPELER ?
         ============================================================ -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow eyebrow--purple">Quand appeler <?= htmlspecialchars($ligne) ?>&nbsp;?</span>

                    <p class="intro-split-lead" style="margin-bottom: 0;">
                        <strong>Pas sûre d'être concernée&nbsp;?</strong> Notre
                        ligne d'écoute, formée à une approche bienveillante et sans
                        jugement, est ouverte à toute personne qui s'interroge sur
                        une situation de violence.
                    </p>
                </div>

                <aside class="fact-card" aria-label="Deux bonnes raisons d'appeler">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Un doute, une peur</h3>
                                <p>Si vous éprouvez des doutes, des peurs ou des questionnements sur une situation de violence.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Un besoin concret</h3>
                                <p>Si vous avez besoin de renseignements, ou simplement de soutien.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </section>

    <!-- ============================================================
         TROIS MISSIONS PRINCIPALES
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt" id="orientation">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Trois missions principales</span>

            <div class="programmes-grid">

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Mission 1</span>
                    <h3>L'écoute</h3>
                    <p>
                        Un espace confidentiel, sans jugement, pour poser des
                        mots sur ce que vous vivez, à votre rythme.
                    </p>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Mission 2</span>
                    <h3>L'information</h3>
                    <p>
                        Des repères clairs sur vos droits et les démarches à
                        suivre.
                    </p>
                    <div class="tag-list">
                        <span class="tag">Pratique</span>
                        <span class="tag">Psychologique</span>
                        <span class="tag">Juridique</span>
                    </div>
                </article>

                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                        </svg>
                    </div>
                    <span class="duo-card-tag">Mission 3</span>
                    <h3>L'orientation</h3>
                    <p>
                        Vers un premier entretien chez nous, ou vers nos
                        partenaires du
                        <a href="/pilier-accompagnement.php">Pôle Accompagnement</a>.
                    </p>
                </article>

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
