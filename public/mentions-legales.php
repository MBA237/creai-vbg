<?php
declare(strict_types=1);
session_start();

$legal = require __DIR__ . '/../config/legal.php';

$pageTitle = 'Mentions légales — CREAI-VBG';
$pageCss   = 'mentions-legales.css';
$widePage  = true;

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

require __DIR__ . '/partials/header.php';
?>

<main class="page-content">

<section class="page-hero page-hero--compact">
    <div class="page-hero-content">
        <span class="page-badge">Informations légales</span>
        <h1>Mentions légales et confidentialité</h1>
        <p class="page-lead">Dernière mise à jour : <?= $e($legal['derniere_maj']) ?></p>
    </div>
</section>

<section class="page-section">
    <div class="page-container page-container--narrow legal">

        <!-- ------------------------------------------------------------
             ÉDITEUR
             ------------------------------------------------------------ -->
        <h2>Éditeur du site</h2>
        <dl class="legal-list">
            <div>
                <dt>Dénomination</dt>
                <dd><?= $e($legal['nom_complet']) ?></dd>
            </div>
            <div>
                <dt>Statut</dt>
                <dd><?= $e($legal['forme']) ?></dd>
            </div>
            <?php if ($legal['recepisse'] !== ''): ?>
                <div>
                    <dt>Récépissé de déclaration</dt>
                    <dd><?= $e($legal['recepisse']) ?></dd>
                </div>
            <?php endif; ?>
            <div>
                <dt>Siège social</dt>
                <dd><?= $e($legal['siege']) ?></dd>
            </div>
            <div>
                <dt>Email</dt>
                <dd><a href="mailto:<?= $e($legal['email']) ?>"><?= $e($legal['email']) ?></a></dd>
            </div>
            <?php if ($legal['telephone'] !== ''): ?>
                <div>
                    <dt>Téléphone</dt>
                    <dd><?= $e($legal['telephone']) ?></dd>
                </div>
            <?php endif; ?>
            <?php if ($legal['directeur_publication'] !== ''): ?>
                <div>
                    <dt>Directeur de la publication</dt>
                    <dd><?= $e($legal['directeur_publication']) ?></dd>
                </div>
            <?php endif; ?>
        </dl>

        <?php if ($legal['hebergeur_nom'] !== ''): ?>
            <h2>Hébergement</h2>
            <dl class="legal-list">
                <div>
                    <dt>Hébergeur</dt>
                    <dd><?= $e($legal['hebergeur_nom']) ?></dd>
                </div>
                <?php if ($legal['hebergeur_adresse'] !== ''): ?>
                    <div>
                        <dt>Adresse</dt>
                        <dd><?= $e($legal['hebergeur_adresse']) ?></dd>
                    </div>
                <?php endif; ?>
                <?php if ($legal['hebergeur_contact'] !== ''): ?>
                    <div>
                        <dt>Contact</dt>
                        <dd><?= $e($legal['hebergeur_contact']) ?></dd>
                    </div>
                <?php endif; ?>
            </dl>
        <?php endif; ?>

        <!-- ------------------------------------------------------------
             PROPRIÉTÉ INTELLECTUELLE
             ------------------------------------------------------------ -->
        <h2>Propriété intellectuelle</h2>
        <p>
            Sauf mention contraire, les textes, images, logos et autres contenus de ce site
            sont la propriété du CREAI-VBG ou utilisés avec l'autorisation de leurs auteurs.
            Toute reproduction ou réutilisation, totale ou partielle, sans autorisation
            préalable est interdite. Pour toute demande, écrivez-nous à
            <a href="mailto:<?= $e($legal['email']) ?>"><?= $e($legal['email']) ?></a>.
        </p>

        <!-- ------------------------------------------------------------
             DONNÉES PERSONNELLES
             ------------------------------------------------------------ -->
        <h2>Données personnelles</h2>
        <p>
            Toute information partagée avec le CREAI-VBG est traitée avec la plus stricte
            confidentialité, conformément à notre code de conduite et d'éthique.
            Voici ce que ce site collecte, et pourquoi.
        </p>

        <h3>Formulaire de contact</h3>
        <p>
            Nom, adresse email, sujet, message et adresse IP. Ces données servent uniquement
            à traiter votre demande et à vous répondre.
        </p>

        <h3>Demande d'adhésion</h3>
        <p>
            Nom, adresse email, catégorie de membre souhaitée et message éventuel. Ces données
            servent à examiner votre demande par notre Conseil d'Administration.
        </p>

        <h3>Signalement en ligne</h3>
        <p>
            Le formulaire de <a href="/signalement.php">signalement</a> recueille le type de violence,
            une description, le lieu et la date approximatifs, et l'indication d'un éventuel danger
            immédiat ou de la présence d'une personne mineure. <strong>Aucun nom ni contact n'est
            obligatoire</strong> : un prénom ou un pseudo et un moyen de vous joindre ne sont
            enregistrés que si vous choisissez de les indiquer, et uniquement pour vous répondre.
            Ce formulaire <strong>n'enregistre pas votre adresse IP</strong>. Les signalements sont lus
            uniquement par les administrateurs habilités du CREAI-VBG.
        </p>

        <h3>Assistant en ligne</h3>
        <p>
            Les messages que vous saisissez dans l'assistant sont transmis à un prestataire
            d'intelligence artificielle tiers afin de générer une réponse. Le site ne les
            enregistre pas dans sa base de données et la conversation disparaît quand vous
            quittez la page. <strong>Ne saisissez pas d'informations permettant de vous
            identifier</strong> (nom, adresse, numéro de téléphone). L'assistant ne remplace
            pas une aide humaine : en cas de danger, contactez les services d'urgence ou
            consultez la page <a href="/besoin-aide.php">Besoin d'aide ?</a>.
        </p>

        <h3>Cookies et services externes</h3>
        <p>
            Le site utilise uniquement un cookie de session technique, nécessaire à la sécurité
            des formulaires. Il n'utilise ni outil de mesure d'audience, ni cookie publicitaire.
            Les polices d'écriture sont chargées depuis les serveurs de Google (Google Fonts),
            ce qui transmet votre adresse IP à ce service lors de l'affichage des pages.
            Comme pour tout site web, les journaux techniques du serveur d'hébergement peuvent
            conserver votre adresse IP, indépendamment de ce site.
        </p>

        <h3>Vos droits</h3>
        <p>
            Vous pouvez demander l'accès à vos données, leur rectification ou leur suppression
            en écrivant à <a href="mailto:<?= $e($legal['email']) ?>"><?= $e($legal['email']) ?></a>.
        </p>

        <!-- ------------------------------------------------------------
             NAVIGATION SÉCURISÉE
             ------------------------------------------------------------ -->
        <h2>Votre sécurité en ligne</h2>
        <p>
            Si vous craignez que quelqu'un consulte votre appareil, utilisez la navigation
            privée de votre navigateur et pensez à fermer la page après votre visite. La page
            <a href="/besoin-aide.php">Besoin d'aide ?</a> propose un bouton pour quitter
            rapidement le site.
        </p>

    </div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
