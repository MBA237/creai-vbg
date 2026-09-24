<?php
declare(strict_types=1);

/**
 * Documents téléchargeables affichés sur /apropos.php (section « Cadre juridique et documents »).
 *
 * Déposez chaque fichier dans public/documents/ puis indiquez son nom ci-dessous.
 * Un document dont le fichier n'existe pas n'est PAS affiché : le site ne
 * propose jamais de lien cassé.
 */
return [
    [
        'titre'       => "Statuts de l'association",
        'description' => "Le cadre qui définit nos missions, notre organisation et notre gouvernance.",
        'fichier'     => 'statuts-creai-vbg.pdf',
    ],
    [
        'titre'       => "Code de conduite et d'éthique",
        'description' => "Nos engagements en matière de confidentialité, de respect et de protection des personnes.",
        'fichier'     => 'code-de-conduite-creai-vbg.pdf',
    ],
];
