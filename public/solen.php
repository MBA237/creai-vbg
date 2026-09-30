<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/SolenAdhesion.php';

$situations = SolenAdhesion::SITUATIONS;

$errors    = [];
$success   = false;
$startStep = 1;   // étape du formulaire ouverte au chargement (voir js/wizard.js)
$old     = [
    'prenom'                  => '',
    'nom'                     => '',
    'pseudo'                  => '',
    'email'                   => '',
    'telephone'               => '',
    'telephone_proche'        => '',
    'date_naissance'          => '',
    'profession'              => '',
    'ville'                   => '',
    'situation'                => '',
    'accepte_confidentialite' => '',
    'accepte_charte'          => '',
    'accepte_whatsapp'        => '',
    'accepte_newsletter'      => '',
];

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    }

    foreach (['prenom', 'nom', 'pseudo', 'email', 'telephone', 'telephone_proche', 'date_naissance', 'profession', 'ville', 'situation'] as $field) {
        $old[$field] = trim($_POST[$field] ?? '');
    }
    foreach (['accepte_confidentialite', 'accepte_charte', 'accepte_whatsapp', 'accepte_newsletter'] as $field) {
        $old[$field] = !empty($_POST[$field]) ? '1' : '';
    }

    // Étape 1 : « Vos informations »
    $errorsBeforeStep1 = count($errors);

    if ($old['prenom'] === '')                                 $errors[] = 'Le prénom est obligatoire.';
    if (mb_strlen($old['prenom']) > 100)                       $errors[] = 'Le prénom est trop long (100 caractères max).';
    if ($old['nom'] === '')                                    $errors[] = 'Le nom est obligatoire.';
    if (mb_strlen($old['nom']) > 100)                          $errors[] = 'Le nom est trop long (100 caractères max).';
    if (mb_strlen($old['pseudo']) > 100)                       $errors[] = 'Le pseudo est trop long (100 caractères max).';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL))     $errors[] = 'Email invalide.';
    if ($old['telephone'] === '')                              $errors[] = 'Le téléphone est obligatoire.';
    if (mb_strlen($old['telephone']) > 30)                     $errors[] = 'Le téléphone est trop long (30 caractères max).';
    if ($old['telephone_proche'] === '')                       $errors[] = "Le téléphone d'un(e) proche de confiance est obligatoire.";
    if (mb_strlen($old['telephone_proche']) > 30)              $errors[] = "Le téléphone d'un(e) proche de confiance est trop long (30 caractères max).";
    if ($old['ville'] === '')                                  $errors[] = 'La ville ou région est obligatoire.';
    if (mb_strlen($old['ville']) > 150)                        $errors[] = 'La ville ou région est trop longue (150 caractères max).';
    if (mb_strlen($old['profession']) > 150)                   $errors[] = 'La profession est trop longue (150 caractères max).';

    $dateNaissance = DateTime::createFromFormat('Y-m-d', $old['date_naissance']);
    if ($old['date_naissance'] === '' || !$dateNaissance || $dateNaissance->format('Y-m-d') !== $old['date_naissance']) {
        $errors[] = 'Date de naissance invalide.';
    } elseif ($dateNaissance > new DateTime()) {
        $errors[] = 'La date de naissance ne peut pas être dans le futur.';
    }

    $step1Failed = count($errors) > $errorsBeforeStep1;

    // Étape 2 : « Votre situation » et « Confidentialité »
    if (!array_key_exists($old['situation'], $situations))     $errors[] = 'Veuillez indiquer votre situation.';
    if ($old['accepte_confidentialite'] !== '1') $errors[] = 'Veuillez accepter la politique de confidentialité.';
    if ($old['accepte_charte'] !== '1')          $errors[] = 'Veuillez confirmer avoir pris connaissance de la Charte de bienveillance.';
    if ($old['accepte_whatsapp'] !== '1')        $errors[] = "Veuillez autoriser l'ajout de votre numéro aux groupes WhatsApp de la communauté.";

    if (empty($errors)) {
        try {
            (new SolenAdhesion())->create($old);
            $success = true;
            $old = array_map(static fn () => '', $old);
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } catch (Throwable $e) {
            error_log('[solen.php] ' . $e->getMessage());
            $errors[] = 'Une erreur est survenue. Merci de réessayer.';
        }
    }

    // Après une erreur, on rouvre l'étape où se trouve le problème : la 2 si l'étape 1
    // était correcte (et le jeton valide), sinon la 1.
    if (!empty($errors) && !$step1Failed && $errorsBeforeStep1 === 0) {
        $startStep = 2;
    }
}

$e = static fn (string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');

$pageTitle = 'Solen — Communauté de soutien entre survivant(e)s — CREAI-VBG';
$pageCss   = 'solen.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="pilier-detail-page">

    <!-- ============================================================
         HERO
         ============================================================ -->
    <section class="pilier-detail-hero pilier-detail-hero--purple">
        <div class="pilier-detail-hero-content">

            <nav class="pilier-detail-breadcrumb">
                <a href="/">Accueil</a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <a href="/pilier-accompagnement.php">Accompagnement</a>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
                <span>Programme Solen</span>
            </nav>

            <span class="pilier-detail-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Programme Solen
            </span>

            <h1>Solen</h1>

            <p class="pilier-detail-lead">
                Une présence continue, par et pour les survivant(e)s. Briser
                l'isolement&nbsp;: ensemble, veiller les unes sur les autres.
            </p>

            <div class="hero-don"><?php require __DIR__ . '/partials/hero-don.php'; ?></div>
        </div>
    </section>

    <!-- ============================================================
         INTRODUCTION
         ============================================================ -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <div class="intro-split">

                <div class="intro-split-main">
                    <span class="eyebrow eyebrow--purple">Une communauté de soutien 24h/24 et 7j/7</span>

                    <p class="intro-split-lead">
                        Quand on est survivant(e), on a souvent
                        <strong>plus besoin de présence que de conseils</strong>.
                        Solen réunit ancien(ne)s et actuelles survivant(e)s, où
                        chacune veille sur l'autre — avec un pôle d'urgence
                        toujours disponible pour les demandes spécifiques.
                    </p>

                    <div class="intro-split-actions">
                        <a href="#adhesion" class="btn btn--primary">
                            Rejoindre la communauté
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                        <a href="#charte-integrale" class="btn btn--outline">Lire la charte de bienveillance</a>
                    </div>
                </div>

                <aside class="fact-card" aria-label="Solen en un coup d'œil">
                    <ul class="fact-list">
                        <li class="fact-item fact-item--main">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Disponible 24h/24 et 7j/7</h3>
                                <p>Une présence continue, discrète et rassurante : personne n'est seule dans l'obscurité.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Par et pour les survivant(e)s</h3>
                                <p>Une entraide entre ancien(ne)s et actuelles survivant(e)s, sans jugement.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Un pôle d'urgence à vos côtés</h3>
                                <p>Juridique, social, psychologique, santé : des professionnel(le)s sollicité(e)s à tout moment.</p>
                            </div>
                        </li>
                        <li class="fact-item">
                            <span class="fact-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>
                            <div>
                                <h3>Stricte confidentialité</h3>
                                <p>Un groupe WhatsApp privé et sécurisé. Ce qui est partagé reste dans la communauté.</p>
                            </div>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
    </section>

    <!-- ============================================================
         FONDEMENTS ET VISION
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Fondements et vision</span>

            <div class="solen-vision-layout">

                <div class="solen-vision-text">
                    <p>
                        Solen part d'un constat clinique : le lien intime toxique
                        crée une dépendance psychologique comparable aux
                        mécanismes des autres addictions.
                    </p>
                    <p>
                        Inspiré des communautés de pairs (AA, NA), le programme
                        pose un postulat fort : la présence humaine, le soutien
                        mutuel et la reconnaissance du vécu priment sur les
                        conseils hâtifs.
                    </p>
                </div>

                <div>
                    <h3 class="solen-tools-title">Quatre outils de reconstruction</h3>
                    <div class="tile-grid tile-grid--2">
                        <div class="tile">
                            <span class="tile-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                            Le groupe
                        </div>
                        <div class="tile">
                            <span class="tile-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                            </span>
                            La parole libérée
                        </div>
                        <div class="tile">
                            <span class="tile-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="17 1 21 5 17 9"/>
                                    <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                                    <polyline points="7 23 3 19 7 15"/>
                                    <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
                                </svg>
                            </span>
                            La répétition rassurante
                        </div>
                        <div class="tile">
                            <span class="tile-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="8" height="18" rx="1"/>
                                    <rect x="13" y="3" width="8" height="18" rx="1"/>
                                </svg>
                            </span>
                            L'effet miroir
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         DEUX PILIERS COMPLÉMENTAIRES
         ============================================================ -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">« Solen » : deux piliers complémentaires</span>

            <div class="duo-grid">
                <div class="duo-card">
                    <span class="duo-card-tag">Pilier 1 · 24h/24, 7j/7</span>
                    <h3>La communauté d'entraide et de veille</h3>
                    <p>Un espace sécurisé pour briser l'isolement.</p>
                    <div class="tag-list">
                        <span class="tag">Mise en relation entre pairs</span>
                        <span class="tag">Écoute inconditionnelle</span>
                        <span class="tag">Repérage des signaux d'alerte</span>
                    </div>
                </div>

                <div class="duo-card">
                    <span class="duo-card-tag">Pilier 2 · Sur demande</span>
                    <h3>Pôle d'urgence et d'accompagnement spécialisé</h3>
                    <p>Un réseau d'expert(e)s prend le relais pour sécuriser et accompagner.</p>
                    <div class="tag-list">
                        <span class="tag">Volet juridique</span>
                        <span class="tag">Volet social</span>
                        <span class="tag">Volet psychologique</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FONDEMENTS ÉTHIQUES ET OPÉRATIONNELS
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Quatre engagements stricts</span>

            <div class="programmes-grid">
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <h3>Sécurité et confidentialité</h3>
                    <p>Protection absolue des données, de l'anonymat et de la sécurité des participantes.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3>Non-jugement, respect du rythme</h3>
                    <p>Un accompagnement adapté au cheminement de chacune, sans injonction.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        </svg>
                    </div>
                    <h3>Complémentarité des expertises</h3>
                    <p>Le savoir des pairs (effet miroir) et le savoir professionnel du pôle d'urgence.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                        </svg>
                    </div>
                    <h3>Rôle de passerelle</h3>
                    <p>Ne remplace pas les secours vitaux : un sas sécurisé, d'orientation et de suivi.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- ============================================================
         LA PHILOSOPHIE DE SOLEN
         ============================================================ -->
    <section class="pilier-detail-section">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">La philosophie de Solen</span>

            <div class="pilier-detail-container--narrow" style="padding: 0; max-width: 820px;">
                <div class="pilier-quote">
                    <p>
                        On ne sauve pas à la place de l'autre. On lui permet de
                        retrouver l'énergie de se battre pour elle-même. On
                        accepte les rechutes. On avance, un jour à la fois, et on
                        veille.
                    </p>
                    <cite>Philosophie du programme Solen</cite>
                </div>
            </div>

            <div class="grid-3-2" style="margin-top: 8px;">
                <article class="programme-card">
                    <span class="num-badge" aria-hidden="true">1</span>
                    <h3>Refuser le rôle de sauveur toute-puissant</h3>
                    <p>Respecter la souveraineté et le rythme de la personne, jamais décider à sa place.</p>
                </article>
                <article class="programme-card">
                    <span class="num-badge" aria-hidden="true">2</span>
                    <h3>Raviver la flamme intérieure</h3>
                    <p>Créer l'espace pour que chacune puise en elle la force de se reconstruire.</p>
                </article>
                <article class="programme-card">
                    <span class="num-badge" aria-hidden="true">3</span>
                    <h3>Accueillir la réalité des rechutes</h3>
                    <p>La rechute n'est pas un échec, mais une étape du cheminement. On reste là.</p>
                </article>
                <article class="programme-card">
                    <span class="num-badge" aria-hidden="true">4</span>
                    <h3>Un jour à la fois</h3>
                    <p>La guérison de l'emprise ne se fait pas en un jour, mais avec une présence ininterrompue.</p>
                </article>
                <article class="programme-card">
                    <span class="num-badge" aria-hidden="true">5</span>
                    <h3>Veiller sans étouffer</h3>
                    <p>Une présence constante, discrète et rassurante, pour que personne ne soit plus jamais seule.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CHARTE DE BIENVEILLANCE
         ============================================================ -->
    <section class="pilier-detail-section pilier-detail-section--alt" id="charte-integrale">
        <div class="pilier-detail-container">
            <span class="eyebrow eyebrow--purple">Charte de bienveillance des « Solen »</span>

            <div class="charte-intro">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0;">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <span>
                    La communauté des « Solen » repose sur la bienveillance, la
                    confidentialité et le respect du rythme de chacune.
                </span>
            </div>

            <div class="grid-3-2" style="margin-top: 24px;">
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3>Non-discrimination et inclusion</h3>
                    <p>Aucune discrimination (origine, âge, croyances, handicap, sexe…) envers quiconque fréquente Solen.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <h3>Respect et communication bienveillante</h3>
                    <p>Respect, douceur, écoute sans interrompre. Aucun jugement, aucune parole blessante.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </div>
                    <h3>Esprit d'entraide et collaboration</h3>
                    <p>Solidarité, écoute active et reconnaissance envers les parcours de chacun·e.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                            <line x1="9" y1="9" x2="9.01" y2="9"/>
                            <line x1="15" y1="9" x2="15.01" y2="9"/>
                        </svg>
                    </div>
                    <h3>Résolution pacifique des conflits</h3>
                    <p>Désamorcer les tensions par une communication constructive, jamais par la critique stérile.</p>
                </article>
                <article class="programme-card">
                    <div class="programme-icon programme-icon--purple">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <h3>Intégrité, confidentialité et sécurité</h3>
                    <p>Rien de ce qui est partagé ne sort du groupe. Un espace sûr, sans manipulation ni prosélytisme.</p>
                </article>
            </div>

            <p class="charte-note">
                Cette charte peut être affichée dans la documentation d'accueil,
                épinglée au début du groupe WhatsApp ou intégrée au processus de
                validation après le remplissage du formulaire d'adhésion.
            </p>
        </div>
    </section>

    <!-- ============================================================
         FORMULAIRE D'ADHÉSION
         ============================================================ -->
    <section class="pilier-detail-section" id="adhesion">
        <div class="pilier-detail-container">
            <div class="solen-layout">

            <!-- Colonne d'infos (même esprit que « coordonnées » sur la page Contact) -->
            <aside class="solen-aside">
                <span class="eyebrow eyebrow--purple">Formulaire d'adhésion</span>

                <p class="solen-aside-intro">
                    Intégrer la communauté Solen, c'est trouver un espace
                    d'écoute et de soutien bienveillant, en toute liberté et à
                    votre rythme.
                </p>

                <ul class="fact-list">
                    <li class="fact-item">
                        <span class="fact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        </span>
                        <div>
                            <h3>Un pseudo, si vous le souhaitez</h3>
                            <p>Le nom sous lequel vous apparaîtrez dans la communauté. Sans réponse, on vous le demandera avant votre arrivée.</p>
                        </div>
                    </li>
                    <li class="fact-item">
                        <span class="fact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                        </span>
                        <div>
                            <h3>Un groupe WhatsApp privé</h3>
                            <p>Dédié, sécurisé : les échanges s'y poursuivent après votre adhésion.</p>
                        </div>
                    </li>
                    <li class="fact-item">
                        <span class="fact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <div>
                            <h3>Stricte confidentialité</h3>
                            <p>Chaque information partagée est traitée avec la plus grande discrétion.</p>
                        </div>
                    </li>
                    <li class="fact-item">
                        <span class="fact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </span>
                        <div>
                            <h3>Une charte à respecter</h3>
                            <p>Pour que la communauté reste un espace sûr. <a href="#charte-integrale">Lire la charte</a></p>
                        </div>
                    </li>
                </ul>

              
            </aside>

            <div class="solen-form-card">

                <?php if ($success): ?>
                    <div class="solen-success">
                        <div class="solen-success-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <h2>Merci pour votre confiance</h2>
                        <p>
                            Votre demande d'adhésion à la communauté Solen a bien
                            été enregistrée. Une personne de notre équipe revient
                            vers vous, en toute confidentialité, pour vous
                            accueillir dans le groupe.
                        </p>
                        <a href="/besoin-aide.php" class="btn btn--outline">Retour à « Besoin d'aide »</a>
                    </div>
                <?php else: ?>

                    <p class="solen-form-intro">
                        Les champs marqués d'un <span class="req">*</span> sont
                        obligatoires.
                    </p>

                    <?php if ($errors): ?>
                        <div class="solen-alert solen-alert--error" role="alert">
                            <strong>Veuillez corriger les erreurs suivantes :</strong>
                            <ul>
                                <?php foreach ($errors as $err): ?>
                                    <li><?= $e($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="#adhesion" class="solen-form wizard-form" data-start-step="<?= (int) $startStep ?>">
                        <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">

                        <noscript>
                            <div class="solen-alert solen-alert--error">
                                Ce formulaire en deux étapes nécessite JavaScript. Sans lui, écrivez-nous via la
                                <a href="/contact.php">page Contact</a> : nous vous répondrons en toute confidentialité.
                            </div>
                        </noscript>

                        <!-- Progression -->
                        <ol class="solen-stepper" aria-hidden="true">
                            <li class="wizard-dot is-active" data-dot="1">
                                <span class="solen-stepper-num">1</span>
                                <span class="solen-stepper-label">Vos informations</span>
                            </li>
                            <li class="wizard-dot" data-dot="2">
                                <span class="solen-stepper-num">2</span>
                                <span class="solen-stepper-label">Votre situation</span>
                            </li>
                        </ol>
                        <p class="solen-step-label">Étape <span data-current-step><?= (int) $startStep ?></span> sur 2</p>

                        <!-- ===================== ÉTAPE 1 ===================== -->
                        <div class="solen-step" data-step="1">

                        <fieldset class="solen-fieldset">
                            <legend class="solen-legend">Vos informations</legend>

                            <div class="solen-grid">
                                <div class="solen-row">
                                    <label for="prenom">Prénom <span class="req">*</span></label>
                                    <input type="text" id="prenom" name="prenom" required maxlength="100"
                                           value="<?= $e($old['prenom']) ?>">
                                </div>

                                <div class="solen-row">
                                    <label for="nom">Nom <span class="req">*</span></label>
                                    <input type="text" id="nom" name="nom" required maxlength="100"
                                           value="<?= $e($old['nom']) ?>">
                                </div>

                                <div class="solen-row solen-row--full">
                                    <label for="pseudo">Pseudo</label>
                                    <input type="text" id="pseudo" name="pseudo" maxlength="100"
                                           value="<?= $e($old['pseudo']) ?>"
                                           placeholder="Le nom sous lequel vous souhaitez apparaître dans la communauté">
                                    <small>
                                        Si vous ne répondez pas, on vous le
                                        demandera avant votre arrivée dans le
                                        groupe.
                                    </small>
                                </div>

                                <div class="solen-row">
                                    <label for="email">E-mail <span class="req">*</span></label>
                                    <input type="email" id="email" name="email" required
                                           value="<?= $e($old['email']) ?>">
                                </div>

                                <div class="solen-row">
                                    <label for="telephone">Téléphone <span class="req">*</span></label>
                                    <input type="tel" id="telephone" name="telephone" required maxlength="30"
                                           value="<?= $e($old['telephone']) ?>" placeholder="+237 6XX XX XX XX">
                                </div>

                                <div class="solen-row solen-row--full">
                                    <label for="telephone_proche">Téléphone d'un(e) proche de confiance <span class="req">*</span></label>
                                    <input type="tel" id="telephone_proche" name="telephone_proche" required maxlength="30"
                                           value="<?= $e($old['telephone_proche']) ?>" placeholder="+237 6XX XX XX XX">
                                </div>

                                <div class="solen-row">
                                    <label for="date_naissance">Date de naissance <span class="req">*</span></label>
                                    <input type="date" id="date_naissance" name="date_naissance" required
                                           value="<?= $e($old['date_naissance']) ?>">
                                </div>

                                <div class="solen-row">
                                    <label for="profession">Profession</label>
                                    <input type="text" id="profession" name="profession" maxlength="150"
                                           value="<?= $e($old['profession']) ?>">
                                </div>

                                <div class="solen-row solen-row--full">
                                    <label for="ville">Ville ou région <span class="req">*</span></label>
                                    <input type="text" id="ville" name="ville" required maxlength="150"
                                           value="<?= $e($old['ville']) ?>">
                                </div>
                            </div>
                        </fieldset>

                        <div class="solen-form-footer">
                            <p class="solen-form-note">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                Vos informations restent strictement confidentielles.
                            </p>
                            <button type="button" class="btn btn--primary wizard-next">
                                Continuer
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </button>
                        </div>

                        </div><!-- /étape 1 -->

                        <!-- ===================== ÉTAPE 2 ===================== -->
                        <div class="solen-step" data-step="2" hidden>

                        <fieldset class="solen-fieldset">
                            <legend class="solen-legend">
                                Votre situation <span class="req">*</span>
                            </legend>

                            <?php foreach ($situations as $value => $label): ?>
                                <label class="solen-choice">
                                    <input type="radio" name="situation" value="<?= $e($value) ?>"
                                           <?= $old['situation'] === $value ? 'checked' : '' ?> required>
                                    <span><?= $e($label) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </fieldset>

                        <fieldset class="solen-fieldset">
                            <legend class="solen-legend">Confidentialité</legend>

                            <label class="solen-choice">
                                <input type="checkbox" name="accepte_confidentialite" value="1"
                                       <?= $old['accepte_confidentialite'] === '1' ? 'checked' : '' ?> required>
                                <span>
                                    J'accepte la
                                    <a href="/mentions-legales.php" target="_blank" rel="noopener noreferrer">politique de confidentialité</a>. <span class="req">*</span>
                                </span>
                            </label>

                            <label class="solen-choice">
                                <input type="checkbox" name="accepte_charte" value="1"
                                       <?= $old['accepte_charte'] === '1' ? 'checked' : '' ?> required>
                                <span>
                                    J'ai pris connaissance de la
                                    <a href="#charte-integrale">Charte de bienveillance</a>
                                    et je m'engage à la respecter. <span class="req">*</span>
                                </span>
                            </label>

                            <label class="solen-choice">
                                <input type="checkbox" name="accepte_whatsapp" value="1"
                                       <?= $old['accepte_whatsapp'] === '1' ? 'checked' : '' ?> required>
                                <span>
                                    J'accepte que le CREAI-VBG utilise mon
                                    numéro pour m'ajouter aux groupes WhatsApp
                                    de la communauté. <span class="req">*</span>
                                </span>
                            </label>

                            <label class="solen-choice">
                                <input type="checkbox" name="accepte_newsletter" value="1"
                                       <?= $old['accepte_newsletter'] === '1' ? 'checked' : '' ?>>
                                <span>J'accepte de recevoir la newsletter du CREAI-VBG.</span>
                            </label>
                        </fieldset>

                        <div class="solen-form-footer">
                            <button type="button" class="btn btn--outline wizard-prev">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <line x1="19" y1="12" x2="5" y2="12"/>
                                    <polyline points="12 19 5 12 12 5"/>
                                </svg>
                                Retour
                            </button>
                            <button type="submit" class="btn btn--primary">Rejoindre la communauté Solen</button>
                        </div>

                        </div><!-- /étape 2 -->
                    </form>

                <?php endif; ?>
            </div><!-- /.solen-form-card -->

            </div><!-- /.solen-layout -->
        </div>
    </section>

    <!-- ============================================================
         CTA FINAL
         ============================================================ -->
    <section class="pilier-detail-cta">
        <div class="pilier-detail-container">
            <h2>Une urgence, avant de rejoindre Solen ?</h2>
            <p>
                Si vous êtes en danger immédiat, ne passez pas par ce
                formulaire : utilisez nos canaux d'urgence dédiés.
            </p>
            <div class="pilier-detail-cta-buttons">
                <a href="/besoin-aide.php" class="btn btn--primary">Besoin d'aide</a>
                <a href="/pilier-accompagnement.php" class="btn btn--outline">Retour à l'accompagnement</a>
            </div>
        </div>
    </section>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
