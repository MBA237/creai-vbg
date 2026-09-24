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
         VOUS ÊTES... (onglets par situation)
         ============================================================ -->
    <section class="aide-section role-section">
        <div class="aide-container">
            <div class="aide-section-head">
                <span class="eyebrow eyebrow--coral">Vous êtes...</span>
                <p class="aide-section-intro">
                    La violence n'a pas de genre&nbsp;: on peut la subir quel que soit son
                    genre, et on peut aussi l'exercer quel que soit son genre. Choisissez
                    la situation qui vous correspond pour accéder aux informations adaptées.
                </p>
            </div>

            <div class="role-tabs" role="tablist" aria-label="Choisissez votre situation">
                <button type="button" class="role-tab is-active" role="tab" id="role-tab-besoin" aria-controls="role-panel-besoin" aria-selected="true" data-role="besoin">
                    <span class="role-tab-num">1</span> J'ai besoin d'aide
                </button>
                <button type="button" class="role-tab" role="tab" id="role-tab-proche" aria-controls="role-panel-proche" aria-selected="false" tabindex="-1" data-role="proche">
                    <span class="role-tab-num">2</span> Je veux aider un proche
                </button>
                <button type="button" class="role-tab" role="tab" id="role-tab-pro" aria-controls="role-panel-pro" aria-selected="false" tabindex="-1" data-role="pro">
                    <span class="role-tab-num">3</span> Je suis professionnel
                </button>
                <button type="button" class="role-tab" role="tab" id="role-tab-violent" aria-controls="role-panel-violent" aria-selected="false" tabindex="-1" data-role="violent">
                    <span class="role-tab-num">4</span> J'ai des comportements violents
                </button>
            </div>

            <div class="role-panels">

                <!-- 1. J'ai besoin d'aide -->
                <div class="role-panel is-active" id="role-panel-besoin" role="tabpanel" aria-labelledby="role-tab-besoin" data-role-panel="besoin">
                    <p class="role-lead">
                        Vous êtes confronté(e) à une situation difficile ou craignez pour
                        votre sécurité&nbsp;? Voici des solutions rapides et adaptées pour
                        comprendre, être écouté(e), accompagné(e) ou orienté(e).
                    </p>

                    <div class="role-cards-wrap">
                        <button type="button" class="role-cards-nav role-cards-nav--prev" data-scroll="prev" aria-label="Voir les cartes précédentes">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="15 18 9 12 15 6"/>
                            </svg>
                        </button>
                        <div class="role-cards-scroll">
                            <div class="role-block role-block--alert">
                                <h3>Urgence / aide immédiate</h3>
                                <p>
                                    En cas de danger immédiat, contactez les services d'urgence. En
                                    attendant leur arrivée&nbsp;: éloignez-vous si possible de la
                                    personne violente, prévenez une personne de confiance, et
                                    conservez des preuves si cela ne vous met pas davantage en danger
                                    (enregistrements audio, vidéos, photos).
                                </p>
                                <a href="#urgence" class="role-link">Voir les numéros d'urgence ↓</a>
                            </div>

                            <div class="role-block">
                                <h3>Auto-test&nbsp;: le violentomètre</h3>
                                <p>
                                    Difficile de mettre des mots sur ce que l'on subit&nbsp;? Nos
                                    auto-tests aident à objectiver le vécu, provoquer un déclic et
                                    amorcer une démarche vers un accompagnement adapté.
                                </p>
                                <a href="https://aidgbv.colibri-cric.org/violentometre" class="btn btn--outline-coral" target="_blank" rel="noopener noreferrer">Faire le test</a>
                            </div>

                            <div class="role-block">
                                <h3>AidgbvChat <span class="role-soon">Bientôt disponible</span></h3>
                                <p>
                                    Un chatbot IA qui offre un moyen sûr, anonyme et accessible
                                    d'identifier les abus, d'explorer les options disponibles et de
                                    documenter en toute sécurité des informations importantes.
                                </p>
                            </div>

                            <div class="role-block">
                                <h3>Carte des services</h3>
                                <p>
                                    Consultez la carte des services destinée à orienter et répondre
                                    aux besoins des victimes de violences de genre sur le territoire
                                    camerounais, accessible sur la plateforme AidGBV et sur
                                    l'application AidGBV.
                                </p>
                                <a href="https://aidgbv.colibri-cric.org/structures-referencees" class="role-link" target="_blank" rel="noopener noreferrer">Voir la carte sur AidGBV ↗</a>
                            </div>

                            <div class="role-block">
                                <h3>Conseil en ligne</h3>
                                <p>
                                    Un service de conseil en ligne professionnel, anonyme, personnalisé
                                    et gratuit, avec un délai de réponse de trois à cinq jours ouvrables.
                                </p>
                                <a href="https://aidgbv.colibri-cric.org/ligne_ecoute" class="role-link" target="_blank" rel="noopener noreferrer">Accéder au conseil en ligne ↗</a>
                            </div>

                            <div class="role-block">
                                <h3>Solen — communauté de soutien <span class="role-soon">Bientôt disponible</span></h3>
                                <p>
                                    Une communauté-refuge solidaire, disponible 24h/24 et 7j/7,
                                    entièrement fondée sur l'entraide entre victimes de violences
                                    sexistes, avec un accompagnement personnalisé par des pairs dans
                                    un cadre hautement sécurisé.
                                </p>
                                <button type="button" class="btn btn--outline-teal" disabled>Découvrir Solen</button>
                            </div>
                        </div>
                        <button type="button" class="role-cards-nav role-cards-nav--next" data-scroll="next" aria-label="Voir les cartes suivantes">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="9 18 15 12 9 6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- 2. Je veux aider un proche -->
                <div class="role-panel" id="role-panel-proche" role="tabpanel" aria-labelledby="role-tab-proche" data-role-panel="proche" hidden>
                    <p class="role-lead">
                        Soutenir sans juger, orienter sans imposer&nbsp;: accepter qu'on ne
                        sauvera pas, mais qu'on plante une graine. Quand un(e) proche
                        traverse des violences sexistes et sexuelles, la présence, l'écoute
                        et des repères concrets font souvent la différence.
                    </p>

                    <div class="role-block">
                        <h3>Écouter et soutenir</h3>
                        <ul class="role-list">
                            <li><strong>Croire la victime</strong> et lui dire que ce qui s'est passé est inacceptable.</li>
                            <li><strong>Écouter sans juger</strong>, sans remettre en cause son récit ni la forcer à en dire plus qu'elle ne le souhaite.</li>
                            <li><strong>Écouter activement</strong>&nbsp;: reformuler pour montrer une attention réelle, sans chercher à résoudre le problème à sa place.</li>
                            <li><strong>Accompagner avec patience</strong>, sans attendre de résultat immédiat.</li>
                            <li><strong>Respecter son rythme et ses décisions</strong>&nbsp;: elle doit reprendre le contrôle de ses choix.</li>
                            <li><strong>Déculpabiliser</strong>&nbsp;: ce n'est pas sa faute, l'agresseur est seul responsable.</li>
                            <li><strong>Demander de quoi elle a besoin</strong> et se renseigner sur les ressources disponibles.</li>
                            <li><strong>Proposer des services concrets</strong>&nbsp;: l'accompagner, l'aider dans ses démarches, ses courses, prendre des notes lors d'entretiens.</li>
                        </ul>
                    </div>

                    <div class="role-block">
                        <h3>Les mots qui aident</h3>
                        <ul class="phrase-list">
                            <li>« Je te crois. »</li>
                            <li>« Tu as bien fait de venir me voir. »</li>
                            <li>« Merci de ta confiance. »</li>
                            <li>« C'est courageux. »</li>
                            <li>« Tu n'y es pour rien. »</li>
                            <li>« La loi interdit ces violences. »</li>
                            <li>« Je peux t'aider. »</li>
                        </ul>
                    </div>

                    <div class="role-block">
                        <h3>Elle ne s'est pas confiée, mais vous avez des doutes&nbsp;?</h3>
                        <p>
                            Ce n'est pas facile de réagir face à des violences soupçonnées.
                            Renseignez-vous et évitez de prendre une initiative sans en parler
                            avec la personne concernée. Si elle ne souhaite pas se confier,
                            respectez sa décision et assurez-la de votre disponibilité.
                        </p>
                        <a href="https://aidgbv.colibri-cric.org/structures-referencees.php" class="role-link" target="_blank" rel="noopener noreferrer">Services spécialisés pour les proches et témoins ↗</a>
                    </div>

                    <div class="role-block role-block--alert">
                        <h3>La situation devient dangereuse&nbsp;?</h3>
                        <p>S'il y a un danger pour la vie de la personne, appelez immédiatement les secours.</p>
                        <a href="#urgence" class="role-link">Voir les numéros d'urgence ↓</a>
                    </div>

                    <div class="role-block">
                        <h3>Prendre soin de soi</h3>
                        <p>
                            Il est normal de ressentir du malaise, de la colère, de la
                            tristesse ou de l'impuissance face à ce qu'on entend. Rester à
                            l'écoute de son propre ressenti est essentiel pour apporter le
                            meilleur soutien. Des services existent aussi pour vous, proches
                            et témoins.
                        </p>
                        <a href="https://aidgbv.colibri-cric.org" class="role-link" target="_blank" rel="noopener noreferrer">Consulter la plateforme AidGBV ↗</a>
                    </div>

                    <p class="role-callout">Soutenir sans juger</p>

                    <div class="role-block">
                        <h3>Comment orienter efficacement</h3>
                        <p>
                            Suggérer une aide ne veut pas dire décider à la place du proche&nbsp;:
                            c'est être là, sans jugement, en montrant des options claires.
                        </p>
                        <ul class="role-list">
                            <li>Proposer l'auto-test pour aborder le sujet ou confirmer une intuition&nbsp;: <a href="https://aidgbv.colibri-cric.org/violentometre" target="_blank" rel="noopener noreferrer">le violentomètre ↗</a></li>
                            <li>Faire connaître notre communauté d'entraide WhatsApp <span class="role-soon">Bientôt disponible</span></li>
                            <li>Partager nos <a href="https://aidgbv.colibri-cric.org/guides-pratiques" target="_blank" rel="noopener noreferrer">guides pratiques ↗</a> pour mieux comprendre les violences sexistes et savoir comment agir à son échelle.</li>
                        </ul>
                    </div>
                </div>

                <!-- 3. Je suis professionnel -->
                <div class="role-panel" id="role-panel-pro" role="tabpanel" aria-labelledby="role-tab-pro" data-role-panel="pro" hidden>
                    <p class="role-lead">
                        Dans le cadre de votre pratique, vous avez été ou êtes susceptible
                        d'accueillir la parole d'une victime de VBG, et cela vous pose
                        question&nbsp;? Il n'est jamais aisé de savoir comment réagir face à
                        ces situations complexes, et il n'existe pas de canevas unique
                        applicable à toutes les situations : chaque intervention s'adapte au
                        cas par cas. Voici quelques repères.
                    </p>

                    <div class="role-block">
                        <h3>Offrir une écoute de qualité</h3>
                        <p>
                            Une écoute bienveillante permet de rompre l'isolement et d'éviter
                            la victimisation secondaire. Croire la personne et reconnaître sa
                            détresse est une étape indispensable de la reconstruction.
                        </p>
                    </div>

                    <div class="role-block">
                        <h3>Protection et prévention</h3>
                        <p>
                            Vérifiez que la personne est en sécurité et qu'elle a pu bénéficier
                            de soins médicaux adaptés (par exemple, en cas d'agression
                            sexuelle&nbsp;: traitements préventifs IST, prévention des risques
                            de grossesse).
                        </p>
                    </div>

                    <div class="role-block">
                        <h3>Orienter vers une prise en charge</h3>
                        <p>Informez la personne de l'existence de services professionnels spécialisés sur le territoire camerounais.</p>
                        <a href="https://aidgbv.colibri-cric.org" class="role-link" target="_blank" rel="noopener noreferrer">Voir les services recensés sur AidGBV ↗</a>
                    </div>
                </div>

                <!-- 4. J'ai des comportements violents -->
                <div class="role-panel" id="role-panel-violent" role="tabpanel" aria-labelledby="role-tab-violent" data-role-panel="violent" hidden>
                    <p class="role-lead">
                        Vous ressentez le besoin de mieux comprendre certains de vos
                        comportements&nbsp;? Peut-être parce que&nbsp;:
                    </p>
                    <ul class="role-list">
                        <li>on vous a dit que l'un ou l'autre de vos comportements est inapproprié&nbsp;;</li>
                        <li>vous réalisez que certains de vos comportements sont violents&nbsp;;</li>
                        <li>vous prenez conscience des effets dévastateurs des violences sur la personne qui en est la cible&nbsp;;</li>
                        <li>vous vous posez des questions.</li>
                    </ul>
                    <p class="role-lead">
                        Pour y voir plus clair et trouver de l'aide, vous pouvez contacter
                        notre ligne d'écoute, d'information et d'orientation, qui vous permet
                        de parler en toute discrétion des difficultés que vous rencontrez.
                    </p>
                    <a href="#ligne-ecoute" class="role-link">Contacter la ligne d'écoute ↓</a>

                    <div class="role-block">
                        <h3>Pourquoi se faire aider&nbsp;?</h3>
                        <p>
                            Les violences affectent profondément les victimes et leurs proches,
                            mais elles ont aussi des conséquences négatives sur la personne qui
                            les commet. Reconnaître ses comportements et comprendre leur impact
                            est un premier pas crucial vers la prise de responsabilité et le
                            changement.
                        </p>
                        <ul class="role-list">
                            <li>Mieux reconnaître et gérer vos émotions et vos éventuelles pulsions.</li>
                            <li>Comprendre le sens de vos comportements et identifier les émotions qui les sous-tendent.</li>
                            <li>Développer des compétences prosociales&nbsp;: empathie, coopération, communication, respect, entraide, gestion des conflits.</li>
                        </ul>
                        <a href="/pilier-accompagnement.php" class="btn btn--outline-teal">Découvrir notre accompagnement</a>
                    </div>

                    <div class="role-block">
                        <h3>Test&nbsp;: ai-je un comportement violent en couple&nbsp;?</h3>
                        <p>
                            Nul ne trouve le bonheur dans la violence. Sortir de ce schéma
                            commence par une prise de conscience. Ce test interactif
                            d'auto-évaluation vous aide à mettre des mots sur des attitudes,
                            intentionnelles ou non, afin de reprendre le contrôle et d'agir
                            durablement.
                        </p>
                        <a href="https://www.125etapres.org/auto-tests/test-comportement-violent" class="btn btn--outline-coral" target="_blank" rel="noopener noreferrer">Faire le test</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- ============================================================
         URGENCE IMMÉDIATE
         ============================================================ -->
    <?php if (!empty($urgences)): ?>
    <section class="aide-section" id="urgence">
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
    <section class="aide-section aide-section--alt" id="ligne-ecoute">
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
<script src="/js/besoin-aide-tabs.js" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
