<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../src/engagement_forms.php';

// Formulaires bénévole et partenariat : traitement et affichage partagés avec rejoindre.php
$forms = engagement_forms_handle();

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
        <h1>Lutte pour l'égalité de genre et la fin des violences sexistes et sexuelles</h1>
        <p class="page-lead">
            Notre action dépend de la mobilisation de ressources humaines, financières et
            techniques. Faites un don, adhérez, devenez bénévole ou associez votre
            organisation à nos actions.
        </p>
        <div class="page-hero-cta">
            <?php require __DIR__ . '/partials/hero-don.php'; ?>
            <a href="/rejoindre.php#adhesion" class="btn-cta btn-cta--ghost">J'adhère</a>
            <a href="#benevolat" class="btn-cta btn-cta--ghost">Je deviens bénévole</a>
            <a href="#partenaires" class="btn-cta btn-cta--ghost">Je deviens partenaire</a>
        </div>
    </div>
</section>

<!-- ============================================================
     À QUOI SERVENT VOS DONS ?
     ============================================================ -->
<section class="page-section" id="don">
    <div class="page-container">
        <div class="intro-split">

            <div class="intro-split-main">
                <h2 class="section-title">À quoi servent vos dons ?</h2>

                <p class="intro-split-lead">
                    Votre soutien <strong>maintient nos services ouverts</strong>
                    et finance nos actions de terrain, de recherche et de
                    prévention, pour accompagner toujours plus de personnes
                    vivant des violences sexistes et sexuelles.
                </p>

                <div class="intro-split-actions">
                    <a href="/don.php" class="btn-cta btn-cta--primary">Faire un don</a>
                    <a href="/rejoindre.php#adhesion" class="btn-cta btn-cta--ghost">J'adhère</a>
                </div>
            </div>

            <aside class="fact-card" aria-label="L'utilisation de vos dons">
                <ul class="fact-list">
                    <?php
                    // Chaque repère est un accès direct vers la page qui détaille l'action financée.
                    // [lien, classe supplémentaire, icône (contenu du <svg>), titre, texte]
                    $usages = [
                        ['/permanence-telephonique.php', ' fact-item--main',
                         '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
                         "Une ligne d'écoute ouverte 5 jours sur 7",
                         'Financer la formation et la disponibilité de nos écoutant(e)s, pour aider toujours plus de personnes.'],
                        ['/pilier-recherche.php', '',
                         '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
                         'Recherche et transfert de connaissances',
                         'Soutenir la recherche et mieux comprendre les violences sexistes et sexuelles, notamment chez les jeunes.'],
                        ['/pilier-prevention.php', '',
                         '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                         'Prévention et formation',
                         'Multiplier les actions de prévention auprès des communautés et renforcer les formations des professionnel(le)s.'],
                        ['/pilier-innovation.php', '',
                         '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/>',
                         'Outils numériques et sensibilisation',
                         "Développer notre chatbot et l'application AidGBV, enrichir notre site web et nos réseaux sociaux."],
                    ];
                    foreach ($usages as [$href, $modifier, $iconPaths, $titre, $texte]): ?>
                        <li>
                            <a href="<?= htmlspecialchars($href) ?>" class="fact-item fact-item--link<?= $modifier ?>">
                                <span class="fact-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><?= $iconPaths ?></svg>
                                </span>
                                <div>
                                    <h3><?= htmlspecialchars($titre) ?></h3>
                                    <p><?= htmlspecialchars($texte) ?></p>
                                </div>
                                <span class="fact-arrow" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

        </div>
    </div>
</section>

<!-- ============================================================
     COMMENT NOUS SOUTENIR — DONS
     ============================================================ -->
<section class="page-section page-section--alt">
    <div class="page-container">
        <h2 class="section-title">Comment nous soutenir ?</h2>
        <p class="section-intro" style="margin-bottom: 28px;">
            Choisissez la façon de nous soutenir qui vous convient.
        </p>

        <!-- Information importante : paiements en cours d'ouverture -->
        <div class="info-highlight" style="margin: 0 0 36px;">
            <div class="info-highlight-row">
                <div class="info-highlight-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <div class="info-highlight-text">
                    <h3>Les paiements en ligne sont en cours d'ouverture</h3>
                    <p>
                        Une fois votre demande envoyée, notre équipe vous répond
                        rapidement pour finaliser votre don ou votre adhésion.
                    </p>
                </div>
            </div>
        </div>

        <div class="cards-grid cards-grid--3">

            <article class="info-card info-card--coral">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h3>Faire un don ponctuel</h3>
                <p>
                    Vos dons font nos actions ! Que ce soit un petit geste ou une contribution
                    plus importante, chaque montant compte et peut faire une différence
                    significative. Ensemble, nous pouvons transformer des vies.
                </p>
                <a href="/don.php?type=unique" class="info-card-link">Faire un don ponctuel →</a>
            </article>

            <article class="info-card info-card--teal">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 1 1-9-9"/>
                        <polyline points="21 3 21 9 15 9"/>
                    </svg>
                </div>
                <h3>Faire un don mensuel</h3>
                <p>
                    Les dons réguliers sont pour nous extrêmement importants : ils nous
                    permettent d'assurer la pérennité de nos actions sur le long terme,
                    garantir notre indépendance et mener des projets qui changent des vies.
                </p>
                <a href="/don.php?type=mensuel" class="info-card-link">Faire un don mensuel →</a>
            </article>

            <article class="info-card info-card--purple">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Créer une cagnotte</h3>
                <p>
                    Créez une cagnotte solidaire en ligne et rassemblez votre entourage
                    autour de votre projet de collecte au profit du CREAI-VBG.
                </p>
                <a href="/don.php?type=cagnotte" class="info-card-link">Créer une cagnotte →</a>
            </article>

            <article class="info-card info-card--teal">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M19 8v6M22 11h-6"/>
                    </svg>
                </div>
                <h3>J'adhère à la communauté CREAI-VBG</h3>
                <p>
                    Devenez adhérent(e) : vous soutenez financièrement, politiquement et
                    de façon militante nos missions d'aide et d'accompagnement.
                    Adhésion valable du 31 décembre 2026 au 31 décembre 2027.
                </p>
                <a href="/rejoindre.php#adhesion" class="info-card-link">Devenir adhérent(e) →</a>
            </article>

            <article class="info-card info-card--purple">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 11V6a2 2 0 0 0-4 0M14 10V4a2 2 0 0 0-4 0v2M10 10.5V6a2 2 0 0 0-4 0v8"/>
                        <path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>
                    </svg>
                </div>
                <h3>Je souhaite devenir bénévole</h3>
                <p>
                    Sensibilisation, événements, recherche, communication : rejoignez une
                    communauté de bénévoles bienveillante et militante.
                </p>
                <a href="#benevolat" class="info-card-link">Devenir bénévole →</a>
            </article>

            <article class="info-card info-card--gray">
                <div class="info-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286z"/>
                    </svg>
                </div>
                <h3>Devenir partenaire technique ou financier</h3>
                <p>
                    Institutions, entreprises et organisations sont invitées à nous
                    contacter pour construire des partenariats sur mesure.
                </p>
                <a href="#partenaires" class="info-card-link">Devenir partenaire →</a>
            </article>

        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/section-benevolat.php'; ?>

<?php require __DIR__ . '/partials/section-partenaires.php'; ?>


<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="page-cta">
    <div class="page-container">
        <h2>Ensemble, rendons les VBG évitables</h2>
        <p>
            Faites un don, adhérez ou associez votre organisation à nos actions :
            nous vous répondrons pour définir la meilleure façon de contribuer.
        </p>
        <div class="cta-buttons">
            <a href="/don.php" class="btn-cta btn-cta--primary">Faire un don</a>
            <a href="/rejoindre.php" class="btn-cta btn-cta--ghost">Adhérer</a>
        </div>
    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
