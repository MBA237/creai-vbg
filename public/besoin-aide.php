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

// Numéros d'urgence cliquables (appel direct sur mobile), réutilisés à plusieurs endroits
$telLinks = static function (string $class, bool $withLabel = true) use ($urgences, $e): string {
    $out = '';
    foreach ($urgences as $u) {
        $num  = preg_replace('/[^\d+]/', '', $u['numero']) ?? '';
        $out .= '<a href="tel:' . $e($num) . '" class="' . $class . '"><strong>' . $e($u['numero']) . '</strong>'
              . ($withLabel ? ' <span>' . $e($u['libelle']) . '</span>' : '') . '</a>';
    }
    return $out;
};

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
            <?php if (!empty($urgences)): ?>
                <div class="aide-hero-urgences">
                    <span class="aide-hero-urgences-label">En danger immédiat&nbsp;? Appelez&nbsp;:</span>
                    <div class="aide-hero-tels"><?= $telLinks('aide-hero-tel') ?></div>
                </div>
            <?php endif; ?>
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

            <svg class="icon-sprite" width="0" height="0" aria-hidden="true" focusable="false">
                <symbol id="ic-shield-check" viewBox="0 0 24 24"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></symbol>
                <symbol id="ic-message" viewBox="0 0 24 24"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></symbol>
                <symbol id="ic-repeat" viewBox="0 0 24 24"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></symbol>
                <symbol id="ic-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></symbol>
                <symbol id="ic-sliders" viewBox="0 0 24 24"><line x1="21" x2="14" y1="4" y2="4"/><line x1="10" x2="3" y1="4" y2="4"/><line x1="21" x2="12" y1="12" y2="12"/><line x1="8" x2="3" y1="12" y2="12"/><line x1="21" x2="16" y1="20" y2="20"/><line x1="12" x2="3" y1="20" y2="20"/><line x1="14" x2="14" y1="2" y2="6"/><line x1="8" x2="8" y1="10" y2="14"/><line x1="16" x2="16" y1="18" y2="22"/></symbol>
                <symbol id="ic-heart" viewBox="0 0 24 24"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></symbol>
                <symbol id="ic-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></symbol>
                <symbol id="ic-help" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></symbol>
                <symbol id="ic-alert" viewBox="0 0 24 24"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></symbol>
                <symbol id="ic-eye" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></symbol>
                <symbol id="ic-clipboard" viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="m9 14 2 2 4-4"/></symbol>
                <symbol id="ic-book" viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></symbol>
                <symbol id="ic-users" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
            </svg>

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
                                <?php if (!empty($urgences)): ?>
                                    <div class="tel-chips"><?= $telLinks('tel-chip', false) ?></div>
                                <?php endif; ?>
                                <a href="#urgence" class="role-link">Voir le détail des numéros d'urgence ↓</a>
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
                <div class="role-panel role-panel--rich" id="role-panel-proche" role="tabpanel" aria-labelledby="role-tab-proche" data-role-panel="proche" hidden>
                    <p class="role-lead">
                        Soutenir sans juger, orienter sans imposer, accepter qu'on ne sauvera
                        pas, qu'on plante une graine. Quand un(e) proche traverse des violences
                        sexistes et sexuelles, la présence, l'écoute et des repères concrets
                        font souvent la différence. Cette page informe sur les formules à
                        utiliser, celles à bannir, propose des outils et ressources pour
                        comprendre, agir en sécurité et orienter vers une aide adaptée.
                    </p>

                    <section class="role-group role-group--wide" aria-labelledby="proche-ecouter">
                        <header class="role-group-head">
                            <h3 id="proche-ecouter">Conseils pratiques pour écouter et soutenir</h3>
                            <p>
                                Être présent(e) pour un(e) proche victime de violences ne signifie pas
                                le ou la sauver&nbsp;: il s'agit d'écouter sans jugement, de croire la
                                parole exprimée en rappelant que la responsabilité des violences
                                n'appartient jamais à la victime. L'objectif est de devenir un repère
                                dans la durée, sans faire peser d'attentes. Les bonnes pratiques à
                                mettre en place&nbsp;:
                            </p>
                        </header>

                        <ol class="support-grid">
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-shield-check"/></svg></span>
                                <div class="support-body">
                                    <h4>Croire la victime</h4>
                                    <p><em>La première chose à faire est de s'assurer que la victime se sent en sécurité. Il est important de lui dire qu'on la croit et que ce qui s'est passé est inacceptable.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-message"/></svg></span>
                                <div class="support-body">
                                    <h4>Écouter sans juger</h4>
                                    <p><em>Fournir une écoute active et attentive, sans jugement. Ne pas remettre en cause son récit. Valider ses émotions. Il est important de ne pas forcer la victime à parler plus que ce qu'elle a décidé. Il est également déconseillé de faire quoi que ce soit à sa place ou de prendre une initiative qu'elle ne souhaite pas.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-repeat"/></svg></span>
                                <div class="support-body">
                                    <h4>Écouter activement pour comprendre</h4>
                                    <p><em>Offrir un espace sûr à la victime sans chercher à résoudre le problème. Reformuler et vérifier la compréhension pour montrer une attention réelle et créer la confiance.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-clock"/></svg></span>
                                <div class="support-body">
                                    <h4>Accompagner avec patience, sans attendre de résultat immédiat</h4>
                                    <p><em>Les changements prennent du temps et les déclics viennent de multiples sources. Accepter d'agir sans garantie de résultat immédiat et respecter le rythme de votre proche.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-sliders"/></svg></span>
                                <div class="support-body">
                                    <h4>Respecter son rythme et ses décisions</h4>
                                    <p><em>Il faut respecter son rythme et ses besoins, car elle doit pouvoir retrouver le contrôle de ses choix et de sa vie.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-heart"/></svg></span>
                                <div class="support-body">
                                    <h4>Déculpabiliser la victime</h4>
                                    <p><em>Ce n'est pas de sa faute. L'agresseur ou les agresseurs sont les seuls responsables.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-search"/></svg></span>
                                <div class="support-body">
                                    <h4>Demander de quoi elle a besoin ou se renseigner sur les ressources disponibles</h4>
                                    <p><em>Poser ces questions permet d'orienter la victime vers l'aide appropriée&nbsp;:</em></p>
                                    <ul class="support-links">
                                        <li><em>Les lignes d'écoute ou les chatbots&nbsp;: voir la <a href="https://aidgbv.colibri-cric.org" target="_blank" rel="noopener noreferrer">plateforme AidGBV&nbsp;↗</a> et l'application AidGBV.</em></li>
                                        <li><em>La carte des services spécialisés disponibles pour écouter, conseiller, accompagner les personnes victimes de violences&nbsp;: voir la <a href="https://aidgbv.colibri-cric.org" target="_blank" rel="noopener noreferrer">plateforme AidGBV&nbsp;↗</a>.</em></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-users"/></svg></span>
                                <div class="support-body">
                                    <h4>Proposer des services</h4>
                                    <p><em>On peut proposer de lui rendre certains services&nbsp;: l'accompagner, aller récupérer ses affaires dans un lieu où elle ne veut pas se rendre, rechercher des services et se renseigner, faire ses courses, faire son ménage, proposer de prendre des notes lors de ses entretiens avec des services professionnels.</em></p>
                                </div>
                            </li>
                        </ol>
                    </section>

                    <section class="role-group role-group--wide" aria-labelledby="proche-mots">
                        <header class="role-group-head">
                            <h3 id="proche-mots">Que dire à une victime qui se confie&nbsp;?</h3>
                            <p>Voici quelques phrases utiles pour rassurer et soutenir les personnes victimes&nbsp;:</p>
                        </header>

                        <ul class="quote-grid">
                            <li class="quote-card">Je te crois.</li>
                            <li class="quote-card">Tu as bien fait de venir me voir.</li>
                            <li class="quote-card">Merci de ta confiance.</li>
                            <li class="quote-card">Tu as bien fait de m'en parler.</li>
                            <li class="quote-card">C'est courageux.</li>
                            <li class="quote-card">Tu n'y es pour rien.</li>
                            <li class="quote-card">La loi interdit ces violences.</li>
                            <li class="quote-card">Je peux t'aider.</li>
                        </ul>
                    </section>

                    <section class="role-group role-group--wide doubt" aria-labelledby="proche-doutes">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-help"/></svg></span>
                            <h3 id="proche-doutes">Que faire si la personne victime ne s'est pas confiée, mais que tout porte à croire qu'il y a ou qu'il y a eu des violences&nbsp;?</h3>
                            <a href="https://aidgbv.colibri-cric.org/structures-referencees" class="doubt-link" target="_blank" rel="noopener noreferrer">Services spécialisés pour les proches et témoins&nbsp;↗</a>
                        </div>

                        <div class="doubt-body">
                            <p>
                                Ce n'est pas facile de réagir lorsqu'on est témoin de violences ou
                                qu'on soupçonne des violences. On peut éprouver un sentiment
                                d'impuissance, ne pas oser intervenir. On peut aussi avoir peur des
                                conséquences pour la personne ou pour soi-même. Il est important de
                                se renseigner et de <mark>ne pas prendre d'initiative sans en parler
                                avec la personne concernée</mark>.
                            </p>
                            <p>
                                <mark>Des services spécialisés sont disponibles</mark> pour les proches
                                et les personnes témoins de violences. Ils sont là pour écouter,
                                répondre aux questions et conseiller.
                            </p>
                            <p class="doubt-note">
                                La personne ne souhaite pas se confier&nbsp;? <strong>C'est une décision
                                à respecter.</strong> On peut l'assurer d'être là quand elle sera prête.
                            </p>
                        </div>
                    </section>

                    <section class="role-group role-group--wide doubt doubt--alert" aria-labelledby="proche-danger">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-alert"/></svg></span>
                            <h3 id="proche-danger">La situation devient dangereuse&nbsp;?</h3>
                            <a href="#urgence" class="doubt-link doubt-link--coral">Voir le détail des numéros d'urgence&nbsp;↓</a>
                        </div>

                        <div class="doubt-body">
                            <p>S'il y a des craintes pour la vie de la personne, <mark>il faut appeler les numéros d'urgence suivants</mark>&nbsp;:</p>
                            <?php if (!empty($urgences)): ?>
                                <div class="tel-chips"><?= $telLinks('tel-chip') ?></div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="role-group role-group--wide doubt doubt--teal" aria-labelledby="proche-parler">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-heart"/></svg></span>
                            <h3 id="proche-parler">Besoin d'en parler&nbsp;?</h3>
                            <a href="https://aidgbv.colibri-cric.org" class="doubt-link" target="_blank" rel="noopener noreferrer">Consulter la plateforme AidGBV&nbsp;↗</a>
                        </div>

                        <div class="doubt-body">
                            <p>
                                On réagit toutes et tous différemment face à une personne qui se
                                confie à nous ou lorsqu'on est témoin de violences. C'est une
                                question de sensibilité et cela dépend du vécu de chaque personne.
                            </p>
                            <p>
                                Il est normal de ressentir des émotions différentes, comme du
                                malaise, de la colère, de la tristesse, de la peur, de l'impuissance
                                ou encore de l'incompréhension. <mark>Rester à l'écoute de son propre
                                ressenti et veiller à sa santé émotionnelle</mark> est essentiel pour
                                apporter le meilleur soutien possible à la personne concernée.
                            </p>
                            <p class="doubt-note">
                                Voilà pourquoi <strong>des services sont là pour aider et conseiller
                                les proches et les témoins.</strong>
                            </p>
                        </div>
                    </section>

                    <div class="role-group role-group--wide">
                        <p class="role-callout role-callout--banner">Soutenir sans juger</p>
                    </div>

                    <section class="role-group role-group--wide doubt doubt--teal" aria-labelledby="proche-signes">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-eye"/></svg></span>
                            <h3 id="proche-signes">Reconnaître les signes de violence pour mieux accompagner</h3>
                            <a href="https://aidgbv.colibri-cric.org/guides-pratiques" class="doubt-link" target="_blank" rel="noopener noreferrer">Lire nos guides pratiques&nbsp;↗</a>
                        </div>

                        <div class="doubt-body">
                            <p>
                                Reconnaître les signes de violence pour mieux accompagner, c'est
                                <mark>apprendre à repérer les signaux d'alerte chez un proche</mark>
                                pour l'aider efficacement.
                            </p>
                            <p>
                                Nos guides pratiques vous accompagnent pas à pas&nbsp;: de l'écoute
                                bienveillante et sans jugement aux mots justes à employer, en passant
                                par les actions sécuritaires à mener et l'orientation vers les
                                professionnels adaptés, afin que <mark>chaque témoin devienne un
                                acteur clé du soutien aux victimes</mark>.
                            </p>
                        </div>
                    </section>

                    <section class="role-group role-group--wide role-group--last" aria-labelledby="proche-orienter">
                        <header class="role-group-head">
                            <h3 id="proche-orienter">Comment orienter efficacement vers l'aide nécessaire</h3>
                            <p>
                                Suggérer une aide ne veut pas dire décider à la place du proche, mais
                                être là, sans jugement, et lui montrer des options claires. Les
                                repères pour orienter&nbsp;:
                            </p>
                        </header>

                        <ol class="support-grid support-grid--3">
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-clipboard"/></svg></span>
                                <div class="support-body">
                                    <h4>Les tests</h4>
                                    <p><em>Proposer à la victime nos auto-tests pour aborder le sujet ou confirmer une intuition.</em></p>
                                    <a href="https://aidgbv.colibri-cric.org/violentometre" class="support-cta" target="_blank" rel="noopener noreferrer">Faire le violentomètre&nbsp;↗</a>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-users"/></svg></span>
                                <div class="support-body">
                                    <h4>Notre communauté d'entraide</h4>
                                    <p><em>Faire connaître notre communauté d'entraide WhatsApp.</em></p>
                                    <span class="role-soon">Bientôt disponible</span>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-book"/></svg></span>
                                <div class="support-body">
                                    <h4>Outils pratiques</h4>
                                    <p><em>Des ressources pratiques à partager autour de soi pour mieux comprendre les violences sexistes et savoir comment agir à son échelle.</em></p>
                                    <a href="https://aidgbv.colibri-cric.org/guides-pratiques" class="support-cta" target="_blank" rel="noopener noreferrer">Voir les guides pratiques&nbsp;↗</a>
                                </div>
                            </li>
                        </ol>
                    </section>
                </div>

                <!-- 3. Je suis professionnel -->
                <div class="role-panel role-panel--rich" id="role-panel-pro" role="tabpanel" aria-labelledby="role-tab-pro" data-role-panel="pro" hidden>
                    <p class="role-lead">
                        Vous êtes un professionnel qui, dans le cadre de sa pratique, a été ou
                        est susceptible d'accueillir la parole d'une victime de VBG, et cela
                        vous pose question. Précisons, tout d'abord, qu'il n'est jamais aisé de
                        savoir comment réagir face à ces situations complexes.
                    </p>

                    <section class="role-group role-group--wide doubt doubt--teal" aria-labelledby="pro-enjeu">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-help"/></svg></span>
                            <h3 id="pro-enjeu">Que dire&nbsp;? Que faire&nbsp;? Que conseiller&nbsp;?</h3>
                        </div>

                        <div class="doubt-body">
                            <p>
                                Ceci est d'autant plus délicat que <mark>l'enjeu pour la victime est
                                important</mark>. En effet, cette personne a réussi à franchir un certain nombre
                                d'obstacles pour parvenir à se confier, ce qui constitue <mark>une
                                étape cruciale dans le processus de reconstruction</mark>.
                            </p>
                            <p class="doubt-note">
                                Il n'existe pas de canevas précis qui serait applicable pour toutes
                                les situations, puisque celles-ci sont à chaque fois singulières.
                                <strong>Il faut donc adapter son intervention au cas par cas.</strong>
                            </p>
                        </div>
                    </section>

                    <section class="role-group role-group--wide role-group--last" aria-labelledby="pro-reperes">
                        <header class="role-group-head">
                            <h3 id="pro-reperes">Points d'attention pour les professionnels</h3>
                            <p>Néanmoins, nous pouvons relever les points d'attention suivants, qui peuvent servir de repères pour les professionnels&nbsp;:</p>
                        </header>

                        <ol class="support-grid support-grid--3">
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-message"/></svg></span>
                                <div class="support-body">
                                    <h4>Offrir une écoute de qualité</h4>
                                    <p><em>Une écoute bienveillante permet de rompre l'isolement et d'éviter la victimisation secondaire. Il s'agit d'une expérience indispensable préalable à tout processus de reconstruction. Pour ce faire, croire la personne et reconnaître sa détresse est essentiel.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-shield-check"/></svg></span>
                                <div class="support-body">
                                    <h4>Protection et prévention</h4>
                                    <p><em>Vérifier que la personne est à présent en sécurité et qu'elle a pu bénéficier de soins médicaux adaptés (dans le cas d'une agression sexuelle, on peut avoir par exemple les traitements préventifs IST et la prévention des risques de grossesse).</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-search"/></svg></span>
                                <div class="support-body">
                                    <h4>Possibilités de prise en charge de la victime</h4>
                                    <p><em>Informer la personne de l'existence de services professionnels spécialisés sur le territoire camerounais&nbsp;: voir la plateforme AidGBV. Les personnes y trouveront des informations sur l'ensemble des services d'accompagnement qui y sont recensés.</em></p>
                                    <a href="https://aidgbv.colibri-cric.org" class="support-cta" target="_blank" rel="noopener noreferrer">Voir les services recensés sur AidGBV&nbsp;↗</a>
                                </div>
                            </li>
                        </ol>
                    </section>
                </div>

                <!-- 4. J'ai des comportements violents -->
                <div class="role-panel role-panel--rich" id="role-panel-violent" role="tabpanel" aria-labelledby="role-tab-violent" data-role-panel="violent" hidden>
                    <section class="role-group role-group--wide doubt doubt--teal" aria-labelledby="violent-situations">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-help"/></svg></span>
                            <h3 id="violent-situations">Vous ressentez le besoin de mieux comprendre certains de vos comportements&nbsp;?</h3>
                        </div>

                        <div class="doubt-body">
                            <p>C'est peut-être que vous vivez l'une de ces situations&nbsp;:</p>
                            <ul class="doubt-list">
                                <li>On vous a dit que l'un ou l'autre de vos comportements est inapproprié.</li>
                                <li>Vous réalisez que certains de vos comportements sont violents.</li>
                                <li>Vous avez appris, par la presse ou par vos proches, que la loi punit sévèrement les actes de violence.</li>
                                <li>Vous vous rendez compte des effets dévastateurs des violences sur la personne qui en est la cible.</li>
                                <li>Vous vous posez des questions.</li>
                            </ul>
                        </div>
                    </section>

                    <section class="role-group role-group--wide doubt doubt--alert" aria-labelledby="violent-ecoute">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-message"/></svg></span>
                            <h3 id="violent-ecoute">Pour y voir plus clair et trouver de l'aide</h3>
                            <div class="doubt-actions">
                                <?php if ($hasTel): ?>
                                    <a href="tel:<?= $e($tel) ?>" class="doubt-link doubt-link--coral">Appeler la ligne d'écoute&nbsp;: <?= $e($ligne['telephone']) ?></a>
                                <?php endif; ?>
                                <a href="#ligne-ecoute" class="doubt-link doubt-link--ghost">Voir les coordonnées&nbsp;↓</a>
                            </div>
                        </div>

                        <div class="doubt-body">
                            <p>
                                Vous pouvez contacter <mark>notre ligne d'écoute, d'information et
                                d'orientation</mark>, qui vous permet de parler <strong>en toute
                                discrétion</strong> des difficultés que vous rencontrez.
                            </p>
                        </div>
                    </section>

                    <section class="role-group role-group--wide" aria-labelledby="violent-aide">
                        <header class="role-group-head">
                            <h3 id="violent-aide">Pourquoi se faire aider&nbsp;?</h3>
                            <p>
                                Les violences affectent profondément les victimes et leurs proches,
                                mais elles ont aussi des impacts négatifs sur la personne qui les
                                commet.
                            </p>
                            <p>
                                Reconnaître ses propres comportements et comprendre leur impact sur
                                les autres est <strong>un premier pas crucial</strong> vers la prise
                                de responsabilité et le changement. Un deuxième pas serait
                                d'entreprendre une démarche pour modifier ces comportements
                                violents. Notre accompagnement vous permet de&nbsp;:
                            </p>
                        </header>

                        <ol class="support-grid support-grid--3">
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-heart"/></svg></span>
                                <div class="support-body">
                                    <h4>Reconnaître et gérer ses émotions</h4>
                                    <p><em>Mieux reconnaître et gérer vos émotions et vos éventuelles pulsions.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-search"/></svg></span>
                                <div class="support-body">
                                    <h4>Comprendre ses comportements</h4>
                                    <p><em>Comprendre le sens de vos comportements, tenter de les modifier et identifier les émotions présentes en vous.</em></p>
                                </div>
                            </li>
                            <li class="support-card">
                                <span class="support-icon"><svg aria-hidden="true"><use href="#ic-users"/></svg></span>
                                <div class="support-body">
                                    <h4>Développer des compétences prosociales</h4>
                                    <p><em>L'empathie, la coopération, la communication efficace, le respect des autres, l'entraide et la gestion des conflits.</em></p>
                                </div>
                            </li>
                        </ol>

                        <p class="group-cta">
                            <a href="/pilier-accompagnement.php" class="doubt-link">Découvrir notre accompagnement</a>
                        </p>
                    </section>

                    <section class="role-group role-group--wide role-group--last doubt" aria-labelledby="violent-test">
                        <div class="doubt-head">
                            <span class="support-icon"><svg aria-hidden="true"><use href="#ic-clipboard"/></svg></span>
                            <h3 id="violent-test">Test&nbsp;: ai-je un comportement violent en couple&nbsp;?</h3>
                            <a href="https://www.125etapres.org/auto-tests/test-comportement-violent" class="doubt-link doubt-link--coral" target="_blank" rel="noopener noreferrer">Faire le test&nbsp;↗</a>
                        </div>

                        <div class="doubt-body">
                            <p>
                                Nul ne trouve le bonheur dans la violence. Elle inflige une détresse
                                immense aux victimes, tout en enfermant les auteurs dans une spirale
                                de regrets. <mark>Sortir de ce schéma commence par une prise de
                                conscience</mark>, que ce test interactif se propose d'accompagner.
                            </p>
                            <p>
                                Avez-vous des comportements violents ou toxiques au sein de votre
                                couple&nbsp;? Pour vous aider à faire le point, «&nbsp;CREAI-VBG&nbsp;»
                                met à disposition le test d'auto-évaluation interactif
                                «&nbsp;Ai-je un comportement intime violent&nbsp;?&nbsp;».
                            </p>
                            <p class="doubt-note">
                                Il permet de <strong>mettre des mots sur des attitudes, intentionnelles
                                ou non</strong>, afin de reprendre le contrôle et d'agir durablement.
                            </p>
                        </div>
                    </section>
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

<script src="<?= v('/js/besoin-aide-tabs.js') ?>" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
