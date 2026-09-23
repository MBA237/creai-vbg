<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/Signalement.php';

// Page sensible : jamais mise en cache, jamais indexée, pas de « Referer » transmis
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Referrer-Policy: no-referrer');
header('X-Robots-Tag: noindex, nofollow');
$noindex = true;

$aide     = require __DIR__ . '/../config/aide.php';
$urgences = $aide['urgences'];

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$errors = [];
$old    = [
    'pour_qui' => '', 'danger_immediat' => '', 'mineur' => 'inconnu', 'types' => [],
    'description' => '', 'lieu' => '', 'date_faits' => '',
    'contact_nom' => '', 'contact_canal' => '', 'contact_moyen' => '', 'contact_sur' => '', 'contact_consent' => '',
];

// ============================================================
//   ENVOI
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = (string) ($_POST['csrf'] ?? '');

    if (!hash_equals($_SESSION['csrf'], $token)) {
        $errors[] = 'La page a expiré. Rechargez-la puis renvoyez le formulaire (votre texte est conservé ci-dessous).';
    }

    $str = static fn (string $k): string => is_string($_POST[$k] ?? null) ? trim((string) $_POST[$k]) : '';

    $old = [
        'pour_qui'        => $str('pour_qui'),
        'danger_immediat' => !empty($_POST['danger_immediat']) ? '1' : '',
        'mineur'          => $str('mineur') ?: 'inconnu',
        'types'           => Signalement::parseTypes(is_array($_POST['types'] ?? null) ? array_filter($_POST['types'], 'is_string') : []),
        'description'     => $str('description'),
        'lieu'            => $str('lieu'),
        'date_faits'      => $str('date_faits'),
        'contact_nom'     => $str('contact_nom'),
        'contact_canal'   => $str('contact_canal'),
        'contact_moyen'   => $str('contact_moyen'),
        'contact_sur'     => !empty($_POST['contact_sur']) ? '1' : '',
        'contact_consent' => !empty($_POST['contact_consent']) ? '1' : '',
    ];

    // Robot : le champ piège « site_web » est invisible pour une personne. On fait comme si tout allait bien.
    if ($str('site_web') !== '') {
        $_SESSION['signalement_ok'] = ['ref' => 'SIG-' . strtoupper(bin2hex(random_bytes(4))), 'danger' => false];
        header('Location: /signalement.php?envoye=1');
        exit;
    }

    // --- Validation ---
    if (!isset(Signalement::POUR_QUI[$old['pour_qui']])) {
        $errors[] = 'Indiquez qui est concerné(e) par la situation.';
    }
    if (!isset(Signalement::MINEUR[$old['mineur']])) {
        $old['mineur'] = 'inconnu';
    }
    if (empty($old['types'])) {
        $errors[] = 'Choisissez au moins un type de violence (« Autre / je ne sais pas » si vous hésitez).';
    }
    if (mb_strlen($old['description']) < 10) {
        $errors[] = 'Décrivez la situation en quelques mots (10 caractères minimum).';
    } elseif (mb_strlen($old['description']) > 3000) {
        $errors[] = 'La description est trop longue (3000 caractères maximum).';
    }
    if (mb_strlen($old['lieu']) > 150)       $errors[] = 'Le lieu est trop long (150 caractères maximum).';
    if (mb_strlen($old['date_faits']) > 100) $errors[] = 'La date est trop longue (100 caractères maximum).';
    if (mb_strlen($old['contact_nom']) > 150) $errors[] = 'Le nom est trop long (150 caractères maximum).';

    // Coordonnées : entièrement facultatives ; si elles sont données, elles doivent être exploitables
    $moyen = $old['contact_moyen'];
    $canal = $old['contact_canal'];
    if ($moyen !== '') {
        if (!isset(Signalement::CANAUX[$canal])) {
            $errors[] = 'Précisez comment vous joindre (appel, WhatsApp, SMS ou email).';
        } elseif ($canal === 'email' && !filter_var($moyen, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'adresse email n\'est pas valide.';
        } elseif ($canal !== 'email' && !preg_match('/^\+?[\d\s().-]{6,25}$/', $moyen)) {
            $errors[] = 'Le numéro de téléphone n\'est pas valide (ex. +237 6XX XX XX XX).';
        } elseif ($old['contact_consent'] !== '1') {
            $errors[] = 'Cochez la case qui nous autorise à vous recontacter, ou effacez vos coordonnées pour rester anonyme.';
        }
    }

    // --- Limitation (session) : évite l'envoi en rafale sans identifier la personne ---
    $now   = time();
    $times = array_values(array_filter((array) ($_SESSION['sig_times'] ?? []), static fn ($t) => is_int($t) && $now - $t < 3600));
    if (empty($errors)) {
        if (!empty($times) && $now - end($times) < 15) {
            $errors[] = 'Un signalement vient d\'être envoyé. Patientez quelques secondes avant d\'en envoyer un autre.';
        } elseif (count($times) >= 5) {
            $errors[] = 'Trop de signalements envoyés depuis cet appareil. Si c\'est urgent, appelez les secours ou écrivez-nous via la page Contact.';
        }
    }

    // --- Enregistrement ---
    if (empty($errors)) {
        $avecContact = $moyen !== '';
        try {
            $ref = (new Signalement())->create([
                'pour_qui'        => $old['pour_qui'],
                'danger_immediat' => $old['danger_immediat'] === '1',
                'mineur'          => $old['mineur'],
                'types'           => $old['types'],
                'description'     => $old['description'],
                'lieu'            => $old['lieu'] !== '' ? $old['lieu'] : null,
                'date_faits'      => $old['date_faits'] !== '' ? $old['date_faits'] : null,
                'contact_nom'     => $avecContact && $old['contact_nom'] !== '' ? $old['contact_nom'] : null,
                'contact_moyen'   => $avecContact ? $moyen : null,
                'contact_canal'   => $avecContact ? $canal : null,
                'contact_sur'     => $avecContact && $old['contact_sur'] === '1',
            ]);

            $times[] = $now;
            $_SESSION['sig_times']        = $times;
            $_SESSION['signalement_ok']   = ['ref' => $ref, 'danger' => $old['danger_immediat'] === '1'];
            $_SESSION['csrf']             = bin2hex(random_bytes(32));

            header('Location: /signalement.php?envoye=1');
            exit;
        } catch (Throwable $ex) {
            error_log('[signalement.php] ' . $ex->getMessage());
            $errors[] = 'Votre signalement n\'a pas pu être enregistré à cause d\'un problème technique. Votre texte est conservé ci-dessous : réessayez dans un instant, ou appelez / écrivez-nous.';
        }
    }
}

// ============================================================
//   ÉCRAN DE CONFIRMATION (affiché une seule fois)
// ============================================================
$done = null;
if (isset($_GET['envoye'])) {
    $done = $_SESSION['signalement_ok'] ?? null;
    unset($_SESSION['signalement_ok']);
    if ($done === null) {
        header('Location: /signalement.php');
        exit;
    }
}

$pageTitle = 'Signaler une situation — CREAI-VBG';
$pageCss   = 'signalement.css';
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

<?php if ($done): ?>

    <!-- ============================================================
         SIGNALEMENT ENREGISTRÉ
         ============================================================ -->
    <section class="aide-hero aide-hero--compact">
        <div class="aide-hero-content">
            <span class="aide-badge">Signalement enregistré</span>
            <h1>Merci, votre signalement a bien été enregistré</h1>
            <p class="aide-lead">Vous avez fait un pas difficile. Vous n'êtes pas seul(e).</p>
        </div>
    </section>

    <section class="aide-section">
        <div class="aide-container aide-container--narrow">

            <div class="sig-ref" role="status">
                <span class="sig-ref-label">Votre code de référence</span>
                <strong class="sig-ref-code"><?= $e($done['ref']) ?></strong>
                <p>
                    Notez-le si vous le pouvez : il permet de faire référence à votre signalement
                    si vous nous contactez. <strong>Il n'est affiché qu'une seule fois.</strong>
                </p>
            </div>

            <?php if (!empty($done['danger']) && !empty($urgences)): ?>
                <div class="sig-danger" role="alert">
                    <strong>Vous avez indiqué un danger immédiat.</strong>
                    Ce formulaire n'est pas un service d'urgence : appelez sans attendre.
                    <span class="sig-danger-numbers">
                        <?php foreach ($urgences as $u): ?>
                            <a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $u['numero']) ?? '') ?>"><?= $e($u['numero']) ?> <small><?= $e($u['libelle']) ?></small></a>
                        <?php endforeach; ?>
                    </span>
                </div>
            <?php endif; ?>

            <div class="sig-next">
                <h2>Et maintenant ?</h2>
                <ul>
                    <li>Notre équipe étudie votre signalement dans les meilleurs délais. <strong>Ce formulaire n'est pas consulté en continu.</strong></li>
                    <li>Si vous avez laissé un moyen de vous joindre, nous ne l'utiliserons que comme vous l'avez choisi.</li>
                    <li>Si vous n'avez laissé aucun contact, nous ne pourrons pas vous répondre : vous pouvez nous joindre à tout moment en citant votre code.</li>
                </ul>
            </div>

            <div class="sig-actions">
                <a href="/besoin-aide.php" class="btn btn--outline-teal">Voir toutes les aides</a>
                <a href="https://www.google.com" class="btn btn--coral" data-quick-exit>Quitter cette page</a>
            </div>
        </div>
    </section>

<?php else: ?>

    <!-- ============================================================
         FORMULAIRE
         ============================================================ -->
    <section class="aide-hero aide-hero--compact">
        <div class="aide-hero-content">
            <span class="aide-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Signalement confidentiel
            </span>
            <h1>Signaler une situation ou demander de l'aide</h1>
            <p class="aide-lead">
                Vous pouvez nous parler de ce que vous vivez, ou de ce dont vous avez été témoin.
                <strong>Vous pouvez rester anonyme</strong> : aucun nom ni contact n'est demandé,
                et nous n'enregistrons pas votre adresse IP.
            </p>
        </div>
    </section>

    <section class="aide-section">
        <div class="aide-container aide-container--narrow">

            <!-- Rappel d'urgence, toujours visible -->
            <?php if (!empty($urgences)): ?>
                <div class="sig-danger sig-danger--static">
                    <strong>En danger immédiat ? Appelez d'abord les secours.</strong>
                    Ce formulaire n'est <u>pas</u> consulté en continu.
                    <span class="sig-danger-numbers">
                        <?php foreach ($urgences as $u): ?>
                            <a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $u['numero']) ?? '') ?>"><?= $e($u['numero']) ?> <small><?= $e($u['libelle']) ?></small></a>
                        <?php endforeach; ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="sig-errors" role="alert">
                    <strong>Quelques points à corriger :</strong>
                    <ul><?php foreach ($errors as $err): ?><li><?= $e($err) ?></li><?php endforeach; ?></ul>
                </div>
            <?php endif; ?>

            <form method="post" class="sig-form" novalidate>
                <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">

                <!-- Champ piège pour les robots : invisible pour une personne -->
                <div class="sig-hp" aria-hidden="true">
                    <label>Ne pas remplir <input type="text" name="site_web" tabindex="-1" autocomplete="off"></label>
                </div>

                <!-- 1. Qui -->
                <fieldset class="sig-card">
                    <legend><span class="sig-step">1</span> Qui est concerné(e) ? <span class="req">*</span></legend>
                    <?php foreach (Signalement::POUR_QUI as $value => $label): ?>
                        <label class="sig-choice">
                            <input type="radio" name="pour_qui" value="<?= $e($value) ?>" <?= $old['pour_qui'] === $value ? 'checked' : '' ?>>
                            <span><?= $e($label) ?></span>
                        </label>
                    <?php endforeach; ?>

                    <label class="sig-choice sig-choice--danger">
                        <input type="checkbox" name="danger_immediat" value="1" id="danger_immediat" <?= $old['danger_immediat'] === '1' ? 'checked' : '' ?>>
                        <span><strong>Il y a un danger immédiat</strong> : une personne est en danger en ce moment.</span>
                    </label>
                    <p class="sig-hint sig-hint--danger" id="danger-alert" <?= $old['danger_immediat'] === '1' ? '' : 'hidden' ?>>
                        Appelez les secours maintenant.
                        <?php foreach ($urgences as $u): ?>
                            <a href="tel:<?= $e(preg_replace('/[^\d+]/', '', $u['numero']) ?? '') ?>"><?= $e($u['numero']) ?></a>
                        <?php endforeach; ?>
                        Vous pouvez aussi envoyer ce signalement : nous le traiterons en priorité dès que nous le lirons.
                    </p>
                </fieldset>

                <!-- 2. Mineur -->
                <fieldset class="sig-card">
                    <legend><span class="sig-step">2</span> Une personne mineure est-elle concernée ?</legend>
                    <?php foreach (Signalement::MINEUR as $value => $label): ?>
                        <label class="sig-choice">
                            <input type="radio" name="mineur" value="<?= $e($value) ?>" <?= $old['mineur'] === $value ? 'checked' : '' ?>>
                            <span><?= $e($label) ?></span>
                        </label>
                    <?php endforeach; ?>
                </fieldset>

                <!-- 3. Type -->
                <fieldset class="sig-card">
                    <legend><span class="sig-step">3</span> De quel(s) type(s) de violence s'agit-il ? <span class="req">*</span></legend>
                    <p class="sig-hint">Vous pouvez en cocher plusieurs.</p>
                    <div class="sig-choices-grid">
                        <?php foreach (Signalement::TYPES as $value => $label): ?>
                            <label class="sig-choice">
                                <input type="checkbox" name="types[]" value="<?= $e($value) ?>" <?= in_array($value, $old['types'], true) ? 'checked' : '' ?>>
                                <span><?= $e($label) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>

                <!-- 4. Description -->
                <fieldset class="sig-card">
                    <legend><span class="sig-step">4</span> Que s'est-il passé ? <span class="req">*</span></legend>
                    <p class="sig-hint">Écrivez ce que vous voulez et pouvez dire, à votre rythme. Vous n'avez pas à tout raconter.</p>
                    <textarea name="description" id="description" rows="7" maxlength="3000" required
                              placeholder="Décrivez la situation avec vos mots..."><?= $e($old['description']) ?></textarea>
                    <p class="sig-counter"><span id="desc-count">0</span> / 3000</p>

                    <div class="sig-row">
                        <div>
                            <label for="lieu">Où cela s'est-il passé ? <span class="sig-optional">(facultatif)</span></label>
                            <input type="text" id="lieu" name="lieu" maxlength="150" value="<?= $e($old['lieu']) ?>" placeholder="Ville, quartier, région...">
                        </div>
                        <div>
                            <label for="date_faits">Quand ? <span class="sig-optional">(facultatif)</span></label>
                            <input type="text" id="date_faits" name="date_faits" maxlength="100" value="<?= $e($old['date_faits']) ?>" placeholder="Ex : la semaine dernière, en 2025, régulièrement...">
                        </div>
                    </div>
                </fieldset>

                <!-- 5. Contact -->
                <fieldset class="sig-card">
                    <legend><span class="sig-step">5</span> Souhaitez-vous que nous vous recontactions ? <span class="sig-optional">(facultatif)</span></legend>
                    <p class="sig-hint sig-hint--info">
                        <strong>Vous pouvez laisser cette partie vide et rester totalement anonyme.</strong>
                        Ne donnez que des coordonnées sûres : que personne d'autre ne puisse lire vos messages ni répondre à votre place.
                    </p>

                    <div class="sig-row">
                        <div>
                            <label for="contact_nom">Prénom ou pseudo</label>
                            <input type="text" id="contact_nom" name="contact_nom" maxlength="150" value="<?= $e($old['contact_nom']) ?>" autocomplete="off" placeholder="Un prénom ou un pseudo suffit">
                        </div>
                        <div>
                            <label for="contact_canal">Par quel moyen ?</label>
                            <select id="contact_canal" name="contact_canal">
                                <option value="">— Choisir —</option>
                                <?php foreach (Signalement::CANAUX as $value => $label): ?>
                                    <option value="<?= $e($value) ?>" <?= $old['contact_canal'] === $value ? 'selected' : '' ?>><?= $e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <label for="contact_moyen">Votre numéro ou votre email</label>
                    <input type="text" id="contact_moyen" name="contact_moyen" maxlength="255" value="<?= $e($old['contact_moyen']) ?>" autocomplete="off" placeholder="+237 6XX XX XX XX ou adresse@exemple.org">

                    <label class="sig-choice">
                        <input type="checkbox" name="contact_sur" value="1" <?= $old['contact_sur'] === '1' ? 'checked' : '' ?>>
                        <span>Ce moyen est <strong>sûr</strong> : on peut me joindre sans risque.</span>
                    </label>
                    <label class="sig-choice">
                        <input type="checkbox" name="contact_consent" value="1" <?= $old['contact_consent'] === '1' ? 'checked' : '' ?>>
                        <span>J'autorise le CREAI-VBG à me recontacter par ce moyen.</span>
                    </label>
                </fieldset>

                <p class="sig-legal">
                    Vos informations sont traitées avec la plus stricte confidentialité et lues uniquement
                    par les personnes habilitées de l'équipe. Voir les
                    <a href="/mentions-legales.php" target="_blank" rel="noopener">mentions légales</a>.
                </p>

                <div class="sig-submit">
                    <button type="submit" class="btn btn--coral">Envoyer mon signalement</button>
                    <a href="/besoin-aide.php" class="btn btn--outline-teal">Retour aux aides</a>
                </div>
            </form>
        </div>
    </section>

<?php endif; ?>

</div>

<script src="/js/quick-exit.js" defer></script>
<script src="/js/signalement.js" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>
