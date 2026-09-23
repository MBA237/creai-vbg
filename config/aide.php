<?php
declare(strict_types=1);

/**
 * Numéros affichés sur /besoin-aide.php, /signalement.php et /contact.php.
 *
 * ⚠️ À VÉRIFIER AVANT PUBLICATION : ces informations concernent la sécurité de
 * personnes en danger. Confirmez chaque numéro d'urgence auprès des autorités
 * camerounaises et renseignez les coordonnées réelles du CREAI-VBG.
 *
 * Les champs laissés vides ('') ne sont PAS affichés : le site n'affiche
 * jamais de faux numéro.
 */
return [

    // Numéros d'urgence (Cameroun). Ajoutez, retirez ou modifiez les lignes.
    // 'principal' => true : mise en avant visuelle.
    'urgences' => [
        ['numero' => '117', 'libelle' => 'Police secours',        'detail' => 'Danger immédiat, agression en cours', 'principal' => true],
        ['numero' => '113', 'libelle' => 'Gendarmerie nationale', 'detail' => 'Signaler une agression, demander une protection', 'principal' => true],
        ['numero' => '118', 'libelle' => 'Sapeurs-pompiers',      'detail' => 'Secours, blessures, urgence médicale', 'principal' => false],
    ],

    // Ligne d'écoute du CREAI-VBG
    'ligne_ecoute' => [
        'telephone' => '',   // ex. '+237 6XX XX XX XX'
        'whatsapp'  => '',   // ex. '+237 6XX XX XX XX' (peut être le même numéro)
        'horaires'  => '',   // ex. 'Du lundi au vendredi, de 9h00 à 17h00'
    ],
];
