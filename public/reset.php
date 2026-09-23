<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Nouveau mot de passe';
$pageCss   = 'auth.css';

$errors  = [];
$success = false;
$token   = $_GET['token'] ?? $_POST['token'] ?? '';
$email   = null;

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Vérification basique du token (juste sa présence pour la démo)
if ($token === '') {
    $errors[] = 'Token manquant.';
} else {
    // Simulé pour la démo : on accepte n'importe quel token non vide
    $email = 'utilisateur@example.com';
}

// Traitement du POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    }

    $password        = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (strlen($password) < 8) {
        $errors[] = 'Le mot de passe doit faire au moins 8 caractères.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Le mot de passe doit contenir au moins une majuscule.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Le mot de passe doit contenir au moins un chiffre.';
    }
    if ($password !== $passwordConfirm) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }

    if (empty($errors)) {
        $success = true;
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
}

require __DIR__ . '/partials/header.php';
?>

<div class="auth-page">
<div class="auth-container layout-login">

    <!-- Panneau bleu (gauche) -->
    <div class="auth-welcome">
        <h2>New Password</h2>
        <p>Choose a strong password</p>
        <a href="/login.php" class="btn-outline">Login</a>
    </div>

    <!-- Panneau formulaire (droite) -->
    <div class="auth-form">
        <h1>Reset Password</h1>

        <?php if ($success): ?>

            <div class="alert alert-success">
                ✅ Votre mot de passe a été modifié avec succès.
            </div>

            <p class="auth-back-link">
                <a href="/login.php">→ Se connecter</a>
            </p>

        <?php elseif ($email !== null && empty($errors)): ?>

            <p class="auth-hint">
                Choisissez un nouveau mot de passe pour <strong><?= htmlspecialchars($email) ?></strong>.
            </p>

            <form method="post">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="field">
                    <input type="password" id="password" name="password" placeholder="Nouveau mot de passe" required minlength="8">
                    <button type="button" class="field-icon toggle" data-target="password" aria-label="Afficher le mot de passe">
                        <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                <div class="field">
                    <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirmer le mot de passe" required minlength="8">
                    <button type="button" class="field-icon toggle" data-target="password_confirm" aria-label="Afficher le mot de passe">
                        <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                <button type="submit" class="btn-primary">Réinitialiser</button>
            </form>

        <?php else: ?>

            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <p class="auth-back-link">
                <a href="/forgot.php">← Refaire une demande</a>
            </p>

        <?php endif; ?>
    </div>

</div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>