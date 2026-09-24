<?php
declare(strict_types=1);

/**
 * Moyens de paiement affichés après une demande de don ou d'adhésion
 * (/don.php, /rejoindre.php).
 *
 * ⚠️ À RENSEIGNER AVANT PUBLICATION avec les vraies coordonnées du CREAI-VBG.
 * Les champs laissés vides ('') ne sont PAS affichés : le site n'affiche
 * jamais de fausses coordonnées de paiement.
 */
return [

    // Lien de paiement par carte bancaire chez votre prestataire (https://...)
    'carte_url'  => '',

    // Lien PayPal (https://www.paypal.com/... ou paypal.me/...)
    'paypal_url' => '',

    // Mobile Money : un bloc par opérateur
    'mobile_money' => [
        'mtn'    => ['libelle' => 'MTN Mobile Money', 'numero' => '', 'titulaire' => ''],
        'orange' => ['libelle' => 'Orange Money',     'numero' => '', 'titulaire' => ''],
    ],

    // Virement bancaire (affiché uniquement si l'IBAN est renseigné)
    'virement' => ['banque' => '', 'titulaire' => '', 'iban' => '', 'bic' => ''],
];
