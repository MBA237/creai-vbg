<?php
declare(strict_types=1);
session_start();

// Numéros d'urgence et ligne d'écoute : voir config/aide.php (les champs vides ne sont pas affichés)
$aide     = require __DIR__ . '/../config/aide.php';
$urgences = $aide['urgences'];
$ligne    = $aide['ligne_ecoute'];

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

$tel    = preg_replace('/[^\d+]/', '', $ligne['telephone']) ?? '';
$hasTel = strlen(preg_replace('/\D/', '', $tel) ?? '') >= 6;
$wa     = preg_replace('/\D/', '', $ligne['whatsapp']) ?? '';
$hasWa  = strlen($wa) >= 8;

$pageTitle = 'Besoin d\'aide ? — CREAI-VBG';
$pageCss   = 'besoin-aide.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<!-- Quitter rapidement : un clic, ou 3 pressions sur Échap -->
<a href="https://www.google.com" class="quick-exit" data-quick-exit
   title="Quitter ce site immédiatement (ou appuyez 3 fois sur la touche Échap)">
    Quitter rapidement
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>
</a>

<div class="aide-page">

    <!-- ============================================================
         HERO
         ============================================================ -->
    <section class="aide-hero">
        <div class="aide-hero-content">
            <span class="aide-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Besoin d'aide ?
            </span>
            <h1>Vous n'êtes pas seul(e)</h1>
            <p class="aide-lead">
                Si vous êtes concerné(e) par une situation de violence basée sur le
                genre, ou si vous souhaitez signaler une situation, plusieurs canaux
                sont à votre disposition. <strong>Toute information partagée avec le
                CREAI-VBG est traitée avec la plus stricte confidentialité.</strong>
            </p>
        </div>
    </section>

    <!-- ============================================================
         URGENCE IMMÉDIATE
         ============================================================ -->
    <?php if (!empty($urgences)): ?>
    <section class="aide-section">
        <div class="aide-container">
            <div class="aide-section-head">
                <span class="eyebrow eyebrow--coral">Urgence immédiate</span>
                <p class="aide-section-intro">
                    Si vous êtes en danger immédiat ou si quelqu'un est en danger,
                    <strong>mettez-vous en sécurité et appelez les secours</strong>.
                </p>
            </div>

            <div class="urgence-grid">
                <?php foreach ($urgences as $u): ?>
                    <a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $u['numero']) ?? '') ?>"
                       class="urgence-card <?= !empty($u['principal']) ? 'urgence-card--primary' : '' ?>">
                        <span class="urgence-number"><?= $e($u['numero']) ?></span>
                        <span class="urgence-label"><?= $e($u['libelle']) ?></span>
                        <?php if (!empty($u['detail'])): ?>
                            <span class="urgence-detail"><?= $e($u['detail']) ?></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <p class="aide-note">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <span>
                    Vous ne pouvez pas appeler sans risque ? Utilisez le
                    <a href="/signalement.php">signalement en ligne</a> et le bouton
                    « Quitter rapidement » en haut de cette page.
                </span>
            </p>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         NOTRE LIGNE D'ÉCOUTE
         ============================================================ -->
    <section class="aide-section aide-section--alt">
        <div class="aide-container">
            <div class="ecoute-box">
                <div class="ecoute-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                </div>
                <div class="ecoute-content">
                    <span class="eyebrow eyebrow--teal">Notre ligne d'écoute</span>
                    <h2>Besoin d'en parler ?</h2>
                    <p>
                        Chaque appel est un moment d'échange en toute confiance. Nous
                        répondons à toute personne concernée, de près ou de loin, par
                        les violences fondées sur le genre : victimes, proches,
                        professionnel(le)s ou toute personne souhaitant mieux comprendre
                        ou soutenir nos actions.
                    </p>

                    <?php if ($hasTel || $hasWa): ?>
                        <?php if ($ligne['horaires'] !== ''): ?>
                            <p class="ecoute-horaires"><strong><?= $e($ligne['horaires']) ?></strong></p>
                        <?php endif; ?>
                        <div class="ecoute-actions">
                            <?php if ($hasTel): ?>
                                <a href="tel:<?= $e($tel) ?>" class="btn btn--primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    Appeler <?= $e($ligne['telephone']) ?>
                                </a>
                            <?php endif; ?>
                            <?php if ($hasWa): ?>
                                <a href="https://wa.me/<?= $e($wa) ?>" class="btn btn--outline" target="_blank" rel="noopener noreferrer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                                    </svg>
                                    WhatsApp
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <p class="ecoute-horaires">
                            <strong>Notre ligne d'écoute sera bientôt disponible.</strong>
                        </p>
                        <p>En attendant, vous pouvez nous écrire en toute confidentialité :</p>
                        <div class="ecoute-actions">
                            <a href="/signalement.php" class="btn btn--primary">Signaler ou demander de l'aide</a>
                            <a href="/contact.php" class="btn btn--outline">Nous écrire</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         SIGNALER EN LIGNE
         ============================================================ -->
    <section class="aide-section">
        <div class="aide-container">
            <div class="signal-box">
                <div class="signal-box-text">
                    <span class="eyebrow eyebrow--coral">Signalement en ligne</span>
                    <h2>Signaler une situation</h2>
                    <p>
                        Vous êtes concerné(e), ou vous avez connaissance d'une situation de
                        violence ? Vous pouvez la signaler en ligne, <strong>sans donner votre
                        nom ni vos coordonnées</strong>. Nous n'enregistrons pas votre adresse IP.
                    </p>
                    <p class="signal-box-warn">
                        Ce formulaire n'est pas consulté en continu : en cas de danger
                        immédiat, appelez d'abord les secours.
                    </p>
                </div>
                <div class="signal-box-action">
                    <a href="/signalement.php" class="btn btn--coral">Faire un signalement</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         QUE FAIRE ?
         ============================================================ -->
    <section class="aide-section aide-section--alt">
        <div class="aide-container">
            <div class="aide-section-head">
                <span class="eyebrow eyebrow--teal">Que faire ?</span>
                <p class="aide-section-intro">
                    Chaque situation est unique. Voici quelques repères pour vous
                    orienter, à votre rythme.
                </p>
            </div>

            <div class="etapes-grid">
                <div class="etape-card">
                    <div class="etape-num">1</div>
                    <h3>Se mettre en sécurité</h3>
                    <p>
                        Si vous êtes en danger immédiat, appelez les secours ou rendez-vous
                        dans un lieu sûr : chez une personne de confiance, dans un lieu public.
                    </p>
                </div>

                <div class="etape-card">
                    <div class="etape-num">2</div>
                    <h3>Ne pas rester seul(e)</h3>
                    <p>
                        Contactez une personne de confiance, un proche, ou notre équipe.
                        Parler brise l'isolement.
                    </p>
                </div>

                <div class="etape-card">
                    <div class="etape-num">3</div>
                    <h3>Conserver les preuves</h3>
                    <p>
                        Si vous pouvez le faire sans vous mettre en danger, gardez messages,
                        photos et certificats médicaux : ils peuvent être utiles pour la suite.
                    </p>
                </div>

                <div class="etape-card">
                    <div class="etape-num">4</div>
                    <h3>Se faire accompagner</h3>
                    <p>
                        Nous vous accompagnons dans les démarches : écoute, soutien
                        psychologique, juridique et orientation vers les bons services.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         NOS SERVICES
         ============================================================ -->
    <section class="aide-section">
        <div class="aide-container">
            <div class="aide-section-head">
                <span class="eyebrow eyebrow--teal">Nos services</span>
                <p class="aide-section-intro">
                    Une prise en charge psychosociale, médicale, juridique et économique,
                    à votre rythme, avec bienveillance et en toute confidentialité.
                </p>
            </div>

            <div class="services-grid services-grid--3">

                <div class="service-card">
                    <div class="service-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 18v-6a9 9 0 0 1 18 0v6"/>
                            <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>
                        </svg>
                    </div>
                    <h3>Écoute et orientation</h3>
                    <p>Une oreille attentive, sans jugement, qui vous oriente vers les bons interlocuteurs.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3>Accompagnement psychologique</h3>
                    <p>Un espace d'écoute sûr avec un(e) psychologue formé(e) au psychotraumatisme. Individuel, gratuit et confidentiel, avec ou sans dépôt de plainte.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="3" x2="12" y2="21"/>
                            <path d="M5 7h14"/>
                            <path d="M5 7l-3 8a3 3 0 0 0 6 0z"/>
                            <path d="M19 7l-3 8a3 3 0 0 0 6 0z"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                        </svg>
                    </div>
                    <h3>Soutien juridique</h3>
                    <p>Nos juristes vous informent sur vos droits : dépôt de plainte, main courante, procédures, recours. Ils vous accompagnent sans vous représenter devant la justice.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3>Accompagnement au procès</h3>
                    <p>Avant l'audience : entretiens de préparation et visite de la salle. Pendant : une présence à vos côtés. Après : un entretien final et une orientation vers nos partenaires.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                            <line x1="9" y1="9" x2="9.01" y2="9"/>
                            <line x1="15" y1="9" x2="15.01" y2="9"/>
                        </svg>
                    </div>
                    <h3>Groupes de soutien</h3>
                    <p>Partagez votre expérience avec d'autres survivant(e)s, dans un cadre bienveillant et confidentiel. Pas besoin d'avoir fait une démarche individuelle. Calendrier à venir.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <h3>Orientation vers nos partenaires</h3>
                    <p>Des structures médicales, juridiques et sociales de votre région, pour une prise en charge complète et un suivi adapté.</p>
                </div>

            </div>

            <p class="services-cta">
                <a href="/pilier-accompagnement.php" class="btn btn--outline-teal">En savoir plus sur l'accompagnement</a>
            </p>
        </div>
    </section>

    <!-- ============================================================
         AUTEUR(E)S DE VIOLENCES
         ============================================================ -->
    <section class="aide-section aide-section--alt">
        <div class="aide-container aide-container--narrow">
            <div class="auteurs-box">
                <h2>Vous êtes à l'origine de violences et vous voulez changer ?</h2>
                <p>
                    Le CREAI-VBG accueille aussi les auteur(e)s de violences, notamment au sein
                    du couple, qu'ils/elles soient engagé(e)s dans une démarche judiciaire ou
                    volontaire. Ce programme aide à prévenir le passage à l'acte et la récidive,
                    pour mieux protéger les victimes.
                </p>
                <a href="/contact.php?sujet=information" class="btn btn--outline-teal">Nous contacter</a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CONFIDENTIALITÉ ET SÉCURITÉ
         ============================================================ -->
    <section class="aide-section">
        <div class="aide-container aide-container--narrow">
            <div class="confiance-box">
                <div class="confiance-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <polyline points="9 12 11 14 15 10"/>
                    </svg>
                </div>
                <h2>Votre confidentialité, votre sécurité</h2>
                <p>
                    Toute information partagée avec le CREAI-VBG est traitée avec la plus
                    stricte confidentialité, conformément à notre code de conduite et d'éthique.
                </p>
                <ul class="securite-liste">
                    <li>Si quelqu'un peut consulter votre téléphone ou votre ordinateur, utilisez la <strong>navigation privée</strong> de votre navigateur.</li>
                    <li>Le bouton <strong>« Quitter rapidement »</strong> (ou 3 pressions sur Échap) remplace immédiatement cette page par un site neutre.</li>
                    <li>Pensez à <strong>effacer l'historique</strong> après votre visite et à ne pas laisser de trace de nos échanges.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CTA FINAL
         ============================================================ -->
    <section class="aide-cta">
        <div class="aide-container">
            <h2>Demander de l'aide maintenant</h2>
            <p>
                Vous n'avez pas à affronter cela seul(e). Écrivez-nous ou faites un
                signalement : une personne de l'équipe prendra le temps de vous écouter.
            </p>
            <div class="aide-cta-buttons">
                <a href="/signalement.php" class="btn btn--coral">Signaler ou demander de l'aide</a>
                <?php if ($hasTel): ?>
                    <a href="tel:<?= $e($tel) ?>" class="btn btn--outline-coral">Appeler la ligne d'écoute</a>
                <?php else: ?>
                    <a href="/contact.php" class="btn btn--outline-coral">Nous écrire</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<script src="/js/quick-exit.js" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
