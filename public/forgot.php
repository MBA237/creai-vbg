<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Mot de passe oublié';
$pageCss   = 'auth.css';

$errors  = [];
$success = false;

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    }

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
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
        <h2>Forgot Password?</h2>
        <p>Remember it now?</p>
        <a href="/login.php" class="btn-outline">Login</a>
    </div>

    <!-- Panneau formulaire (droite) -->
    <div class="auth-form">
        <h1>Reset Password</h1>

        <?php if ($success): ?>

            <div class="alert alert-success">
                Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.
            </div>

            <p class="auth-back-link">
                <a href="/login.php">← Retour à la connexion</a>
            </p>

        <?php else: ?>

            <?php if ($errors): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <p class="auth-hint">
                Entrez votre email pour recevoir un lien de réinitialisation.
            </p>

            <form method="post">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

                <div class="field">
                    <input type="email" id="email" name="email" placeholder="Email" required autofocus>
                    <span class="field-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="m22 6-10 7L2 6"/>
                        </svg>
                    </span>
                </div>

                <button type="submit" class="btn-primary">Envoyer le lien</button>
            </form>

            <p class="divider">or</p>

            <p class="auth-back-link">
                <a href="/login.php">← Retour à la connexion</a>
            </p>

        <?php endif; ?>
    </div>

</div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>