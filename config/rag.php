<?php
declare(strict_types=1);

/**
 * Base de connaissances interne de l'assistant (RAG) — voir src/Rag.php.
 *
 * L'assistant ne répond sur le CREAI-VBG qu'à partir de ces contenus : pages du site
 * ci-dessous, articles et événements publiés (base de données), numéros d'aide (config/aide.php).
 * L'index se reconstruit tout seul quand une page, un article ou un événement change ;
 * `php rag.php build` force la reconstruction.
 */
return [

    // Pages du site indexées : fichier de public/ => [titre affiché, lien]
    'pages' => [
        'apropos.php'                     => ['À propos du CREAI-VBG', '/apropos.php'],
        'piliers.php'                     => ["Nos piliers d'action", '/piliers.php'],
        'pilier-accompagnement.php'       => ['Pilier Accompagnement', '/pilier-accompagnement.php'],
        'pilier-recherche.php'            => ['Pilier Recherche', '/pilier-recherche.php'],
        'pilier-prevention.php'           => ['Pilier Prévention', '/pilier-prevention.php'],
        'pilier-innovation.php'           => ['Pilier Innovation', '/pilier-innovation.php'],
        'pilier-plaidoyer.php'            => ['Pilier Plaidoyer', '/pilier-plaidoyer.php'],
        'accompagnement-psychosocial.php' => ['Accompagnement psychosocial', '/accompagnement-psychosocial.php'],
        'accompagnement-auteurs.php'      => ["Accompagnement des auteurs de violences", '/accompagnement-auteurs.php'],
        'groupes-soutien.php'             => ['Groupes de soutien', '/groupes-soutien.php'],
        'permanence-telephonique.php'     => ['Permanence téléphonique', '/permanence-telephonique.php'],
        'solen.php'                       => ['Programme Solen', '/solen.php'],
        'besoin-aide.php'                 => ["Besoin d'aide", '/besoin-aide.php'],
        'signalement.php'                 => ['Signaler une situation', '/signalement.php'],
        'soutenir.php'                    => ['Nous soutenir', '/soutenir.php'],
        'rejoindre.php'                   => ['Nous rejoindre (adhésion, bénévolat, partenariat)', '/rejoindre.php'],
        'don.php'                         => ['Faire un don', '/don.php'],
        'partenaires.php'                 => ['Nos partenaires', '/partenaires.php'],
        'notre-equipe.php'                => ['Notre équipe', '/notre-equipe.php'],
        'connaissances.php'               => ['Connaissances', '/connaissances.php'],
        'apprentissage.php'               => ['Apprentissage', '/apprentissage.php'],
        'contact.php'                     => ['Contact', '/contact.php'],
    ],

    // Informations sûres, rédigées à la main, à indexer en plus (aucune limite).
    // Chaque entrée : ['titre' => ..., 'url' => ..., 'texte' => ...]
    'faits' => [],

    // Découpage : taille visée d'un extrait (caractères)
    'chunk_size' => 700,

    // Recherche : nombre max d'extraits transmis à l'assistant, et seuil de pertinence
    'top_k'     => 4,
    'min_score' => 1.2,
];
