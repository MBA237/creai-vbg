<?php
declare(strict_types=1);
session_start();

require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/Membership.php';

$errors  = [];
$success = false;
$old     = ['nom' => '', 'email' => '', 'categorie' => '', 'message' => ''];

$categories = Membership::CATEGORIES;

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    }

    $nom       = trim($_POST['nom'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $message   = trim($_POST['message'] ?? '');
    $old       = compact('nom', 'email', 'categorie', 'message');

    if ($nom === '')                                       $errors[] = 'Le nom est obligatoire.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))        $errors[] = 'Email invalide.';
    if (!array_key_exists($categorie, $categories))         $errors[] = 'Veuillez choisir une catégorie de membre.';

    if (empty($errors)) {
        try {
            (new Membership())->create($nom, $email, $categorie, $message);
            $success = true;
            $old = ['nom' => '', 'email' => '', 'categorie' => '', 'message' => ''];
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
            et votre niveau d'engagement souhaité.
        </p>
    </div>
</section>

<!-- ============================================================
     CATÉGORIES DE MEMBRES
     ============================================================ -->
<section class="rejoindre-section">
    <div class="rejoindre-container">
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
<section class="rejoindre-section rejoindre-section--alt">
    <div class="rejoindre-container">
        <div class="adhesion-grid">

            <div class="adhesion-steps">
                <h2>Comment adhérer ?</h2>
                <ol class="steps-list">
                    <li>
                        <span class="step-num">1</span>
                        <span>Prenez connaissance de nos statuts et de notre code de conduite.</span>
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

                <form method="post">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

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

                    <label for="message">Message (facultatif)</label>
                    <textarea id="message" name="message" rows="4"><?= htmlspecialchars($old['message']) ?></textarea>

                    <button type="submit">Adhérer</button>
                </form>
            </div>

        </div>
    </div>
</section>
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