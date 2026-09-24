<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../src/Contact.php';
require __DIR__ . '/../src/paiement.php';

$h = static fn (mixed $s): string => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

$modes = ['mensuel' => 'Don mensuel', 'unique' => 'Don unique', 'cagnotte' => 'Cagnotte'];
$montants = ['mensuel' => [7, 12, 30], 'unique' => [15, 30, 60]];

$paysTel = [
    '+237' => '🇨🇲', '+33' => '🇫🇷', '+225' => '🇨🇮', '+221' => '🇸🇳', '+234' => '🇳🇬',
    '+235' => '🇹🇩', '+241' => '🇬🇦', '+242' => '🇨🇬', '+243' => '🇨🇩', '+229' => '🇧🇯',
    '+228' => '🇹🇬', '+223' => '🇲🇱', '+226' => '🇧🇫', '+227' => '🇳🇪', '+224' => '🇬🇳',
    '+32' => '🇧🇪', '+1' => '🇨🇦', '+44' => '🇬🇧', '+49' => '🇩🇪', '+41' => '🇨🇭',
];

$defaults = [
    'mode' => 'mensuel', 'amount' => '', 'amount_libre' => '',
    'email' => '', 'association' => '', 'civilite' => '', 'prenom' => '', 'nom' => '',
    'pays_tel' => '+237', 'telephone' => '', 'date_naissance' => '',
    'adresse' => '', 'complement_adresse' => '', 'code_postal' => '', 'ville' => '', 'pays' => 'Cameroun',
    'moyen' => 'carte', 'mobile_operateur' => '', 'mobile_numero' => '',
    'cagnotte_titre' => '', 'cagnotte_objectif' => '', 'cagnotte_description' => '',
];
$old = $defaults;

$errors  = [];
$success = null;

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Pré-sélection du mode via /don.php?type=mensuel|unique|ponctuel|cagnotte
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $type = $_GET['type'] ?? '';
    if ($type === 'ponctuel') $type = 'unique';
    if (is_string($type) && isset($modes[$type])) {
        $old['mode'] = $type;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) {
        $errors[] = 'Token de sécurité invalide.';
    }

    foreach (array_keys($defaults) as $key) {
        $old[$key] = trim((string) ($_POST[$key] ?? $defaults[$key]));
    }
    $old['amount']       = trim((string) ($_POST['don_amount'] ?? ''));
    $old['amount_libre'] = trim((string) ($_POST['don_amount_libre'] ?? ''));
    $old['association']  = isset($_POST['association']) ? '1' : '';

    if (!isset($modes[$old['mode']])) {
        $errors[] = 'Type de don invalide.';
        $old['mode'] = 'mensuel';
    }
    $mode = $old['mode'];

    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
    if ($old['prenom'] === '')                              $errors[] = 'Le prénom est obligatoire.';
    if ($old['nom'] === '')                                 $errors[] = 'Le nom est obligatoire.';
    if (!isset($paysTel[$old['pays_tel']]))                 $old['pays_tel'] = '+237';
    $telDigits = preg_replace('/\D/', '', $old['telephone']) ?? '';
    if (strlen($telDigits) < 6 || strlen($telDigits) > 15)  $errors[] = 'Numéro de téléphone invalide.';

    $montant = null;
    if ($mode === 'cagnotte') {
        if ($old['cagnotte_titre'] === '' || mb_strlen($old['cagnotte_titre']) > 150) {
            $errors[] = 'Donnez un titre à votre cagnotte (150 caractères max).';
        }
        if (mb_strlen($old['cagnotte_description']) < 10) {
            $errors[] = 'Décrivez votre projet de collecte (10 caractères min).';
        }
        if ($old['cagnotte_objectif'] !== '' && (!is_numeric($old['cagnotte_objectif']) || (float) $old['cagnotte_objectif'] < 1)) {
            $errors[] = "L'objectif de la cagnotte doit être un montant positif.";
        }
    } else {
        if ($old['amount'] === 'libre') {
            $libre = str_replace(',', '.', $old['amount_libre']);
            if (!is_numeric($libre) || (float) $libre < 1 || (float) $libre > 1000000) {
                $errors[] = 'Montant libre invalide (minimum 1 €).';
            } else {
                $montant = rtrim(rtrim(number_format((float) $libre, 2, '.', ''), '0'), '.');
            }
        } elseif (ctype_digit($old['amount']) && in_array((int) $old['amount'], $montants[$mode], true)) {
            $montant = $old['amount'];
        } else {
            $errors[] = 'Veuillez choisir un montant.';
        }

        if (!isset(PAIEMENT_MOYENS[$old['moyen']])) {
            $errors[] = 'Veuillez choisir un moyen de paiement.';
            $old['moyen'] = 'carte';
        }
        if ($old['moyen'] === 'mobile') {
            if (!isset(PAIEMENT_OPERATEURS[$old['mobile_operateur']])) $errors[] = 'Veuillez choisir un opérateur Mobile Money.';
            if (strlen(preg_replace('/\D/', '', $old['mobile_numero']) ?? '') < 6) $errors[] = 'Numéro Mobile Money invalide.';
        }
    }

    if (empty($errors)) {
        try {
            $lignes = [];
            if ($mode === 'cagnotte') {
                $lignes[] = 'Demande de cagnotte solidaire';
                $lignes[] = 'Titre : ' . $old['cagnotte_titre'];
                if ($old['cagnotte_objectif'] !== '') $lignes[] = 'Objectif : ' . $old['cagnotte_objectif'] . ' €';
                $lignes[] = "Projet :\n" . $old['cagnotte_description'];
            } else {
                $lignes[] = $modes[$mode] . ' : ' . $montant . ' €' . ($mode === 'mensuel' ? ' par mois' : '');
                $moyenTxt = PAIEMENT_MOYENS[$old['moyen']];
                if ($old['moyen'] === 'mobile') {
                    $moyenTxt .= ' (' . PAIEMENT_OPERATEURS[$old['mobile_operateur']] . ' — ' . $old['mobile_numero'] . ')';
                }
                $lignes[] = 'Moyen de paiement souhaité : ' . $moyenTxt;
            }
            $lignes[] = '';
            if ($old['association'] === '1') $lignes[] = "Don effectué au nom d'une organisation ou d'une société";
            $identite = trim($old['civilite'] . ' ' . $old['prenom'] . ' ' . $old['nom']);
            $lignes[] = 'Contact : ' . $identite;
            $lignes[] = 'Téléphone : ' . $old['pays_tel'] . ' ' . $old['telephone'];
            if ($old['date_naissance'] !== '') $lignes[] = 'Date de naissance : ' . $old['date_naissance'];
            $adresse = trim(implode(', ', array_filter([
                $old['adresse'], $old['complement_adresse'], trim($old['code_postal'] . ' ' . $old['ville']), $old['pays'],
            ], static fn (string $p): bool => $p !== '')));
            if ($adresse !== '') $lignes[] = 'Adresse : ' . $adresse;

            $nomContact = mb_substr(trim($old['prenom'] . ' ' . $old['nom']), 0, 100);
            (new Contact())->create($nomContact, $old['email'], 'don', implode("\n", $lignes), $_SERVER['REMOTE_ADDR'] ?? null);

            $success = [
                'mode'     => $mode,
                'montant'  => $montant,
                'moyen'    => $mode === 'cagnotte' ? null : $old['moyen'],
                'moyens'   => $mode === 'cagnotte' ? [] : paiement_moyens($old['moyen'], $old['mobile_operateur']),
            ];
            $old = $defaults;
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } catch (Throwable $e) {
            $errors[] = 'Une erreur est survenue. Merci de réessayer.';
        }
    }
}

$mode       = $old['mode'];
$isCagnotte = $mode === 'cagnotte';
$amountSel  = $old['amount'];
if ($amountSel !== 'libre' && !(ctype_digit($amountSel) && in_array((int) $amountSel, $montants[$mode] ?? [], true))) {
    $amountSel = (string) ($montants[$mode][0] ?? $montants['mensuel'][0]);
}
$amountsMode = $isCagnotte ? 'mensuel' : $mode;

$pageTitle = 'Faire un don — CREAI-VBG';
$pageCss   = 'don.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<main class="don-page">
    <div class="don-intro">
        <h1>Votre don change des vies</h1>
        <p>
            Chaque contribution finance notre ligne d'écoute, nos actions de
            prévention et l'accompagnement des survivant(e)s de violences
            basées sur le genre. Merci de faire partie du changement.
        </p>
    </div>

<?php if ($success): ?>
    <section class="don-done" aria-live="polite">
        <div class="don-done-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>
        </div>
        <?php if ($success['mode'] === 'cagnotte'): ?>
            <h2>Merci, votre demande de cagnotte est bien reçue !</h2>
            <p>Notre équipe vous contacte rapidement pour ouvrir votre cagnotte solidaire au profit du CREAI-VBG.</p>
        <?php else: ?>
            <h2>Merci pour votre générosité !</h2>
            <p>
                Votre demande de <?= $success['mode'] === 'mensuel' ? 'don mensuel' : 'don unique' ?>
                de <strong><?= $h($success['montant']) ?> €</strong> est bien enregistrée.
                Aucun paiement n'a été prélevé : voici comment finaliser votre don.
            </p>
            <?php
            $moyensPaiement = $success['moyens'];
            $moyensVideMessage = "Nos moyens de paiement en ligne sont en cours d'ouverture : notre équipe vous écrit très prochainement avec les instructions pour finaliser votre don.";
            require __DIR__ . '/partials/moyens-paiement.php';
            ?>
        <?php endif; ?>
        <div class="don-done-actions">
            <a href="/" class="btn-submit-link">Retour à l'accueil</a>
            <a href="/soutenir.php" class="btn-ghost-link">Autres façons de nous soutenir</a>
        </div>
    </section>
<?php else: ?>

    <?php if ($errors): ?>
        <div class="don-errors" role="alert">
            <strong>Veuillez corriger :</strong>
            <ul>
                <?php foreach ($errors as $msg): ?>
                    <li><?= $h($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="don-shell" method="post" action="/don.php" aria-label="Formulaire de don">
        <article class="don-panel">
            <header class="don-panel-header">
                <h2>Mon don</h2>
            </header>

            <div class="don-panel-body">
                <input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>">
                <input type="hidden" name="mode" value="<?= $h($mode) ?>">

                <div class="don-toggle" role="group" aria-label="Type de don"
                     data-montants='<?= $h(json_encode($montants)) ?>'>
                    <?php foreach ($modes as $key => $label): ?>
                        <button type="button" data-mode="<?= $h($key) ?>"
                                class="<?= $mode === $key ? 'is-active' : '' ?>"
                                aria-pressed="<?= $mode === $key ? 'true' : 'false' ?>"><?= $h($label) ?></button>
                    <?php endforeach; ?>
                </div>

                <div data-when="don" <?= $isCagnotte ? 'hidden' : '' ?>>
                    <div class="don-amounts" aria-label="Montants de don" data-amounts>
                        <?php foreach ($montants[$amountsMode] as $value): ?>
                            <label class="amount-option">
                                <input type="radio" name="don_amount" value="<?= $value ?>"
                                       <?= $amountSel === (string) $value ? 'checked' : '' ?> <?= $isCagnotte ? 'disabled' : '' ?>>
                                <span class="amount-label"><?= $value ?> €<?= $amountsMode === 'mensuel' ? ' <small>par mois</small>' : '' ?></span>
                            </label>
                        <?php endforeach; ?>

                        <label class="amount-option amount-option--wide" data-freeform>
                            <input type="radio" name="don_amount" value="libre"
                                   <?= $amountSel === 'libre' ? 'checked' : '' ?> <?= $isCagnotte ? 'disabled' : '' ?>>
                            <span class="amount-label" <?= $amountSel === 'libre' ? 'hidden' : '' ?>>Montant libre</span>
                            <?php if ($amountSel === 'libre'): ?>
                                <input type="number" min="1" step="any" inputmode="decimal" class="amount-freeform-input"
                                       name="don_amount_libre" placeholder="Montant en €" required
                                       value="<?= $h($old['amount_libre']) ?>" <?= $isCagnotte ? 'disabled' : '' ?>>
                            <?php endif; ?>
                        </label>
                    </div>

                    <div class="don-help">
                        <span class="don-help-tag">Montant populaire !</span>
                    </div>
                </div>

                <div class="don-cagnotte" data-when="cagnotte" <?= $isCagnotte ? '' : 'hidden' ?>>
                    <p class="payment-note">
                        Créez une cagnotte solidaire en ligne et rassemblez votre entourage autour
                        de votre projet de collecte au profit du CREAI-VBG. Décrivez-nous votre
                        idée : nous vous aidons à la mettre en place.
                    </p>
                    <div class="don-form">
                        <div class="field field--full">
                            <label for="cag-titre">Titre de la cagnotte *</label>
                            <input id="cag-titre" type="text" name="cagnotte_titre" maxlength="150" required
                                   value="<?= $h($old['cagnotte_titre']) ?>" <?= $isCagnotte ? '' : 'disabled' ?>>
                        </div>
                        <div class="field field--full">
                            <label for="cag-objectif">Objectif de collecte (€)</label>
                            <input id="cag-objectif" type="number" name="cagnotte_objectif" min="1" step="any" inputmode="decimal"
                                   value="<?= $h($old['cagnotte_objectif']) ?>" <?= $isCagnotte ? '' : 'disabled' ?>>
                        </div>
                        <div class="field field--full">
                            <label for="cag-description">Votre projet *</label>
                            <textarea id="cag-description" name="cagnotte_description" rows="5" required minlength="10"
                                      <?= $isCagnotte ? '' : 'disabled' ?>><?= $h($old['cagnotte_description']) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <article class="don-panel">
            <header class="don-panel-header">
                <h2>Mes coordonnées</h2>
            </header>

            <div class="don-panel-body">
                <div class="don-form">
                    <div class="field field--email">
                        <label for="don-email">EMAIL *</label>
                        <div class="input-wrap input-wrap--icon">
                            <span class="field-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 6h16v12H4z"></path>
                                    <path d="m4 7 8 6 8-6"></path>
                                </svg>
                            </span>
                            <input id="don-email" type="email" name="email" required value="<?= $h($old['email']) ?>">
                        </div>
                    </div>

                    <label class="checkbox-inline">
                        <input type="checkbox" name="association" value="1" <?= $old['association'] === '1' ? 'checked' : '' ?>>
                        <span>Je fais un don au nom d’une organisation ou d’une société</span>
                    </label>

                    <div class="field-grid">
                        <div class="field">
                            <label for="don-civilite">Civilité</label>
                            <select id="don-civilite" name="civilite">
                                <option value=""> </option>
                                <option value="Madame" <?= $old['civilite'] === 'Madame' ? 'selected' : '' ?>>Madame</option>
                                <option value="Monsieur" <?= $old['civilite'] === 'Monsieur' ? 'selected' : '' ?>>Monsieur</option>
                            </select>
                        </div>

                        <div class="field">
                            <label for="don-prenom">Prénom *</label>
                            <input id="don-prenom" type="text" name="prenom" required maxlength="60" value="<?= $h($old['prenom']) ?>">
                        </div>

                        <div class="field">
                            <label for="don-nom">Nom *</label>
                            <input id="don-nom" type="text" name="nom" required maxlength="60" value="<?= $h($old['nom']) ?>">
                        </div>

                        <div class="field field--full">
                            <label for="don-telephone">Téléphone *</label>
                            <div class="phone-field">
                                <select id="don-pays-tel" name="pays_tel" class="phone-country" aria-label="Indicatif pays">
                                    <?php foreach ($paysTel as $code => $flag): ?>
                                        <option value="<?= $h($code) ?>" <?= $old['pays_tel'] === $code ? 'selected' : '' ?>><?= $flag ?> <?= $h($code) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input id="don-telephone" type="tel" name="telephone" required maxlength="20"
                                       placeholder="6 00 00 00 00" value="<?= $h($old['telephone']) ?>">
                            </div>
                        </div>

                        <div class="field">
                            <label for="don-date">Date de naissance</label>
                            <div class="input-wrap input-wrap--calendar">
                                <input id="don-date" type="date" name="date_naissance" value="<?= $h($old['date_naissance']) ?>">
                                <span class="field-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                        <path d="M16 3v4M8 3v4M3 10h18"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="field field--full">
                            <label for="don-adresse">Adresse</label>
                            <input id="don-adresse" type="text" name="adresse" maxlength="200" value="<?= $h($old['adresse']) ?>">
                        </div>

                        <div class="field field--full">
                            <label for="don-complement">Complément d'adresse</label>
                            <input id="don-complement" type="text" name="complement_adresse" maxlength="200" value="<?= $h($old['complement_adresse']) ?>">
                        </div>

                        <div class="field">
                            <label for="don-codepostal">Code postal</label>
                            <input id="don-codepostal" type="text" name="code_postal" maxlength="20" value="<?= $h($old['code_postal']) ?>">
                        </div>

                        <div class="field">
                            <label for="don-ville">Ville</label>
                            <input id="don-ville" type="text" name="ville" maxlength="100" value="<?= $h($old['ville']) ?>">
                        </div>

                        <div class="field field--full">
                            <label for="don-pays">Pays</label>
                            <input id="don-pays" type="text" name="pays" maxlength="100" value="<?= $h($old['pays']) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </article>

        <article class="don-panel don-panel--payment">
            <header class="don-panel-header">
                <h2 data-title-don="Mon règlement" data-title-cagnotte="Ma demande"><?= $isCagnotte ? 'Ma demande' : 'Mon règlement' ?></h2>
            </header>

            <div class="don-panel-body">
                <div class="payment-intro">
                    <div class="payment-shield" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3 5 6v6c0 4.5 2.7 7.8 7 9 4.3-1.2 7-4.5 7-9V6l-7-3Z"></path>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                    </div>
                    <p data-when="don" <?= $isCagnotte ? 'hidden' : '' ?>>
                        Aucun paiement n'est prélevé sur cette page. Choisissez le moyen qui vous
                        convient : notre équipe vous transmet ensuite les instructions pour
                        finaliser votre don en toute sécurité.
                    </p>
                    <p data-when="cagnotte" <?= $isCagnotte ? '' : 'hidden' ?>>
                        Envoyez-nous votre projet : notre équipe vous répond rapidement pour
                        ouvrir votre cagnotte solidaire.
                    </p>
                </div>

                <div data-when="don" <?= $isCagnotte ? 'hidden' : '' ?>>
                    <input type="hidden" name="moyen" value="<?= $h($old['moyen']) ?>" <?= $isCagnotte ? 'disabled' : '' ?>>

                    <div class="payment-methods" aria-label="Moyens de paiement">
                        <button type="button" class="payment-method <?= $old['moyen'] === 'carte' ? 'is-selected' : '' ?>" data-method="carte"
                                aria-controls="payment-panel-carte" aria-label="Carte bancaire">
                            <svg viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1.5" y="1.5" width="29" height="19" rx="3" stroke="currentColor" stroke-width="2"></rect>
                                <rect x="1.5" y="7.5" width="29" height="4" fill="currentColor" opacity="0.18"></rect>
                                <path d="M6 15h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                            <span>Carte bancaire</span>
                        </button>

                        <button type="button" class="payment-method <?= $old['moyen'] === 'mobile' ? 'is-selected' : '' ?>" data-method="mobile"
                                aria-controls="payment-panel-mobile" aria-label="Mobile Money">
                            <svg viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="10" cy="11" r="6" stroke="currentColor" stroke-width="2"></circle>
                                <path d="M20 6c2.5 2.5 2.5 7.5 0 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                <path d="M24 3c4.5 4.5 4.5 11.5 0 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                            <span>Mobile Money</span>
                        </button>

                        <button type="button" class="payment-method <?= $old['moyen'] === 'paypal' ? 'is-selected' : '' ?>" data-method="paypal"
                                aria-controls="payment-panel-paypal" aria-label="PayPal">
                            <svg viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="1.5" y="3.5" width="29" height="16" rx="3" stroke="currentColor" stroke-width="2"></rect>
                                <path d="M21 11.5h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                <path d="M5 8.5h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" opacity="0.4"></path>
                            </svg>
                            <span>PayPal</span>
                        </button>

                        <button type="button" class="payment-method <?= $old['moyen'] === 'virement' ? 'is-selected' : '' ?>" data-method="virement"
                                aria-controls="payment-panel-virement" aria-label="Virement bancaire">
                            <svg viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 8 16 2l13 6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"></path>
                                <path d="M6 10v6M12 10v6M20 10v6M26 10v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                                <path d="M3 19h26" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                            <span>Virement bancaire</span>
                        </button>
                    </div>

                    <div class="payment-panel <?= $old['moyen'] === 'carte' ? 'is-open' : '' ?>" id="payment-panel-carte" data-panel="carte">
                        <p class="payment-note">Nous vous envoyons le lien de paiement sécurisé de notre prestataire dès la validation de votre demande. Ne saisissez jamais votre numéro de carte par e-mail.</p>
                    </div>

                    <div class="payment-panel <?= $old['moyen'] === 'mobile' ? 'is-open' : '' ?>" id="payment-panel-mobile" data-panel="mobile">
                        <div class="field field--full">
                            <label for="don-mobile-operateur">Opérateur *</label>
                            <select id="don-mobile-operateur" name="mobile_operateur" required <?= $old['moyen'] === 'mobile' && !$isCagnotte ? '' : 'disabled' ?>>
                                <option value="">— Choisir un opérateur —</option>
                                <?php foreach (PAIEMENT_OPERATEURS as $key => $label): ?>
                                    <option value="<?= $h($key) ?>" <?= $old['mobile_operateur'] === $key ? 'selected' : '' ?>><?= $h($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="field field--full">
                            <label for="don-mobile-numero">Votre numéro Mobile Money *</label>
                            <input id="don-mobile-numero" type="tel" name="mobile_numero" required placeholder="6 00 00 00 00"
                                   value="<?= $h($old['mobile_numero']) ?>" <?= $old['moyen'] === 'mobile' && !$isCagnotte ? '' : 'disabled' ?>>
                        </div>
                    </div>

                    <div class="payment-panel <?= $old['moyen'] === 'paypal' ? 'is-open' : '' ?>" id="payment-panel-paypal" data-panel="paypal">
                        <p class="payment-note">Nous vous indiquerons comment régler votre don par PayPal dès la validation de votre demande.</p>
                    </div>

                    <div class="payment-panel <?= $old['moyen'] === 'virement' ? 'is-open' : '' ?>" id="payment-panel-virement" data-panel="virement">
                        <p class="payment-note">Nous vous transmettrons les coordonnées bancaires du CREAI-VBG dès la validation de votre demande.</p>
                    </div>
                </div>

                <div class="don-submit-row">
                    <button type="submit" class="btn-submit" data-label-don="Valider mon don" data-label-cagnotte="Envoyer ma demande"><?= $isCagnotte ? 'Envoyer ma demande' : 'Valider mon don' ?></button>
                    <span class="submit-plus" aria-hidden="true">＋</span>
                </div>
            </div>
        </article>
    </form>
<?php endif; ?>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
