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

    // Lien de paiement par carte bancaire chez votre prestataire (https://...).
    // Marqueurs remplacés par les valeurs du don : {montant} {reference} {mode} {email}
    // ex. https://prestataire.example/pay?amount={montant}&ref={reference}&email={email}
    // Sans marqueur, le lien est utilisé tel quel (le donateur saisit alors le montant chez le prestataire).
    'carte_url'  => '',

    // Lien PayPal (https://www.paypal.com/... ou paypal.me/...). Mêmes marqueurs :
    // ex. https://www.paypal.me/creaivbg/{montant}EUR
    'paypal_url' => '',

    // Mobile Money : un bloc par opérateur
    'mobile_money' => [
        'mtn'    => ['libelle' => 'MTN Mobile Money', 'numero' => '', 'titulaire' => ''],
        'orange' => ['libelle' => 'Orange Money',     'numero' => '', 'titulaire' => ''],
    ],

    // Virement bancaire (affiché uniquement si l'IBAN est renseigné)
    'virement' => ['banque' => '', 'titulaire' => '', 'iban' => '', 'bic' => ''],
];
