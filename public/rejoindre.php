<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Membership.php';
require __DIR__ . '/../src/engagement_forms.php';
require __DIR__ . '/../src/paiement.php';

// Formulaires bénévole et partenariat (traités si form_type correspond)
$forms = engagement_forms_handle();

$errors  = [];
$success = false;
$old     = ['nom' => '', 'email' => '', 'categorie' => '', 'message' => '', 'don' => ''];

$categories  = Membership::CATEGORIES;
$donMontants = ['5' => '5 €', '10' => '10 €', '20' => '20 €', 'autre' => 'Autre montant'];

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? 'adhesion') === 'adhesion') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    }

    $nom       = trim($_POST['nom'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $message   = trim($_POST['message'] ?? '');
    $don       = trim($_POST['don'] ?? '');
    $old       = compact('nom', 'email', 'categorie', 'message', 'don');

    if ($nom === '')                                       $errors[] = 'Le nom est obligatoire.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))        $errors[] = 'Email invalide.';
    if (!array_key_exists($categorie, $categories))         $errors[] = 'Veuillez choisir une catégorie de membre.';
    if ($don !== '' && !array_key_exists($don, $donMontants)) $errors[] = 'Montant de don invalide.';

    if (empty($errors)) {
        try {
            $fullMessage = $message;
            if ($don !== '') {
                $fullMessage = "Don complémentaire souhaité : {$donMontants[$don]}\n\n" . $message;
            }
            (new Membership())->create($nom, $email, $categorie, trim($fullMessage));
            $success = true;
            $old = ['nom' => '', 'email' => '', 'categorie' => '', 'message' => '', 'don' => ''];
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        } catch (Throwable $e) {
            $errors[] = 'Erreur BDD : ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Nous rejoindre — CREAI-VBG';
$pageCss   = 'rejoindre.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>



<!-- ============================================================
     HERO
     ============================================================ -->
<section class="rejoindre-hero">
    <div class="rejoindre-hero-content">
        <span class="rejoindre-badge">Rejoindre le CREAI-VBG</span>
        <h1>Il y a une place pour vous</h1>
        <p class="rejoindre-lead">
            Nous accueillons plusieurs catégories de membres, selon votre profil
            et votre niveau d'engagement souhaité. Adhésion valable du 31 décembre
            2026 au 31 décembre 2027.
        </p>
        <div class="rejoindre-hero-actions">
            <a href="/don.php" class="btn btn--don">Faire un don</a>
            <a href="#adhesion" class="btn btn--light">J'adhère</a>
            <a href="#benevolat" class="btn btn--ghost-light">Je deviens bénévole</a>
            <a href="#partenaires" class="btn btn--ghost-light">Je deviens partenaire</a>
        </div>
    </div>
</section>

<!-- ============================================================
     À QUOI SERT MON ADHÉSION ?
     ============================================================ -->
<section class="rejoindre-section rejoindre-section--tight-bottom">
    <div class="rejoindre-container">
        <h3 class="plans-title">À quoi sert mon adhésion ?</h3>

        <div class="plans-grid">
            <div class="plan-card plan-card--teal">
                <div class="plan-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="6" rx="7" ry="3"/>
                        <path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6"/>
                        <path d="M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/>
                    </svg>
                </div>
                <h4>Sur le plan financier</h4>
                <p><em>Vous contribuez à soutenir nos missions d'aide et d'accompagnement des personnes victimes de violences.</em></p>
            </div>

            <div class="plan-card plan-card--purple">
                <div class="plan-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 11v2a2 2 0 0 0 2 2h1l3 5V4L6 9H5a2 2 0 0 0-2 2z"/>
                        <path d="M15 8a4 4 0 0 1 0 8"/>
                        <path d="M18 5a8 8 0 0 1 0 14"/>
                    </svg>
                </div>
                <h4>Sur le plan politique</h4>
                <p><em>Vous donnez plus de poids et de visibilité au CREAI-VBG et participez au développement de son indépendance.</em></p>
            </div>

            <div class="plan-card plan-card--coral">
                <div class="plan-card-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <h4>Sur le plan militant</h4>
                <p><em>Vous affirmez votre engagement pour la culture d'égalité et la fin des violences de genre.</em></p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     CATÉGORIES DE MEMBRES
     ============================================================ -->
<section class="rejoindre-section rejoindre-section--tight-top">
    <div class="rejoindre-container">
        <h3 class="plans-title">Nos catégories de membres</h3>

        <div class="categories-grid">
            <div class="categorie-card categorie-card--teal">
                <h3>Membre actif</h3>
                <p>Vous souhaitez vous engager régulièrement dans nos activités et contribuer à la vie de l'association.</p>
            </div>
            <div class="categorie-card categorie-card--purple">
                <h3>Membre sympathisant</h3>
                <p>Vous soutenez notre cause sans nécessairement participer à la gestion courante.</p>
            </div>
            <div class="categorie-card categorie-card--coral">
                <h3>Membre bienfaiteur</h3>
                <p>Vous souhaitez apporter un appui matériel, financier ou technique significatif.</p>
            </div>
            <div class="categorie-card categorie-card--gray">
                <h3>Partenaire institutionnel</h3>
                <p>Votre organisation souhaite s'associer à nos actions dans le cadre d'une convention.</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     COMMENT ADHÉRER + FORMULAIRE
     ============================================================ -->
<section class="rejoindre-section rejoindre-section--alt" id="adhesion">
    <div class="rejoindre-container">
        <div class="adhesion-grid">

            <div class="adhesion-steps">
                <h2>Comment adhérer ?</h2>
                <ol class="steps-list">
                    <li>
                        <span class="step-num">1</span>
                        <span>Prenez connaissance de <a href="/apropos.php#documents">nos statuts et de notre code de conduite</a>.</span>
                    </li>
                    <li>
                        <span class="step-num">2</span>
                        <span>Déposez une demande d'adhésion écrite auprès de notre Bureau Exécutif, via le formulaire ci-contre ou par email.</span>
                    </li>
                    <li>
                        <span class="step-num">3</span>
                        <span>Votre demande est examinée par notre Conseil d'Administration.</span>
                    </li>
                    <li>
                        <span class="step-num">4</span>
                        <span>Une fois agréé(e), vous rejoignez la communauté CREAI-VBG et pouvez prendre part à nos activités.</span>
                    </li>
                </ol>

                <div class="paiement-adhesion" id="paiement-adhesion">
                    <h3>Régler votre adhésion</h3>
                    <?php
                    $moyensPaiement    = paiement_moyens();
                    $moyensVideMessage = "Le règlement en ligne (Mobile Money, PayPal, virement) ouvre bientôt. Une fois votre demande agréée par notre Conseil d'Administration, nous vous indiquons comment régler votre adhésion.";
                    require __DIR__ . '/partials/moyens-paiement.php';
                    ?>
                </div>
            </div>

            <div class="form-card">
                <h2>Formulaire d'adhésion</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success">Merci, votre demande d'adhésion a bien été enregistrée !</div>
                <?php endif; ?>

                <?php if ($errors): ?>
                    <div class="alert alert-error">
                        <strong>Veuillez corriger :</strong>
                        <ul>
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="#adhesion">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                    <input type="hidden" name="form_type" value="adhesion">

                    <label for="nom">Nom complet *</label>
                    <input type="text" id="nom" name="nom" required maxlength="100"
                           value="<?= htmlspecialchars($old['nom']) ?>">

                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($old['email']) ?>">

                    <label for="categorie">Catégorie de membre *</label>
                    <select id="categorie" name="categorie" required>
                        <option value="">— Choisir une catégorie —</option>
                        <?php foreach ($categories as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>" <?= $old['categorie'] === $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="don">Souhaitez-vous faire un don en plus de votre adhésion ?</label>
                    <select id="don" name="don">
                        <option value="">Pas de don</option>
                        <?php foreach ($donMontants as $value => $label): $value = (string) $value; ?>
                            <option value="<?= htmlspecialchars($value) ?>" <?= $old['don'] === $value ? 'selected' : '' ?>>
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="message">Message (facultatif)</label>
                    <textarea id="message" name="message" rows="4"><?= htmlspecialchars($old['message']) ?></textarea>

                    <button type="submit">Adhérer</button>
                </form>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     BÉNÉVOLAT (formulaire en 3 étapes)
     ============================================================ -->
<?php require __DIR__ . '/partials/section-benevolat.php'; ?>

<!-- ============================================================
     PARTENAIRE OU SPONSOR
     ============================================================ -->
<?php require __DIR__ . '/partials/section-partenaires.php'; ?>

<!-- ============================================================
     CTA FINAL
     ============================================================ -->
<section class="rejoindre-cta">
    <div class="rejoindre-container">
        <h2>Une question avant d'adhérer ?</h2>
        <p>
            Notre équipe est à votre disposition pour répondre à toutes vos
            interrogations sur les modalités d'adhésion, les statuts de
            l'association ou nos activités.
        </p>
        <div class="rejoindre-cta-buttons">
            <a href="/contact.php" class="btn btn--primary">Nous contacter</a>
            <a href="/apropos.php" class="btn btn--outline">En savoir plus</a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>