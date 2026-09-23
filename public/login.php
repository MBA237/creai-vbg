<?php
declare(strict_types=1);
session_start();

$pageTitle = 'Connexion';
$pageCss   = 'auth.css';

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/partials/header.php';
?>
<div class="auth-page">
<div class="auth-container layout-login">

    <!-- Panneau bleu (gauche) -->
    <div class="auth-welcome">
        <h2>Hello, Welcome!</h2>
        <p>Don't have an account?</p>
        <a href="/register.php" class="btn-outline">Register</a>
    </div>

    <!-- Panneau formulaire (droite) -->
    <div class="auth-form">
        <h1>Login</h1>

        <form method="post">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

            <div class="field">
                <input type="text" id="email" name="email" placeholder="Email" required autofocus>
                <span class="field-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
            </div>

            <div class="field">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="button" class="field-icon toggle" data-target="password" aria-label="Afficher le mot de passe">
                    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>

            <a href="/forgot.php" class="forgot-link">Forgot Password?</a>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="divider">or login with social platforms</p>

        <div class="socials">
            <a href="#" class="social-btn" aria-label="Google">G</a>
            <a href="#" class="social-btn" aria-label="Facebook">f</a>
            <a href="#" class="social-btn" aria-label="GitHub">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.55v-1.93c-3.2.7-3.87-1.54-3.87-1.54-.52-1.33-1.28-1.68-1.28-1.68-1.05-.72.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.73-1.55-2.55-.29-5.24-1.28-5.24-5.68 0-1.25.45-2.28 1.19-3.08-.12-.29-.51-1.46.11-3.05 0 0 .96-.31 3.15 1.18a10.9 10.9 0 0 1 5.74 0c2.18-1.49 3.14-1.18 3.14-1.18.63 1.59.24 2.76.12 3.05.74.8 1.19 1.83 1.19 3.08 0 4.41-2.7 5.38-5.26 5.66.41.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.55A11.5 11.5 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5z"/>
                </svg>
            </a>
            <a href="#" class="social-btn" aria-label="LinkedIn">in</a>
        </div>
    </div>

</div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>