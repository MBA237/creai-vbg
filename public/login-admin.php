<?php
declare(strict_types=1);
session_start();

// Si déjà connecté → redirige vers admin
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/AdminAuth.php';

if (AdminAuth::check()) {
    header('Location: /admin/membres.php');
    exit;
}

$errors = [];
$oldEmail = '';

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token de sécurité invalide.';
    }

    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $oldEmail = $email;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }
    if ($password === '') {
        $errors[] = 'Mot de passe requis.';
    }

    if (empty($errors)) {
        $auth   = new AdminAuth();
        $result = $auth->login($email, $password);

        if ($result['success']) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            header('Location: /admin/membres.php');
            exit;
        } else {
            $errors[] = $result['error'] ?? 'Identifiants incorrects.';
        }
    }

    // Régénère le token CSRF après échec
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$pageTitle = 'Connexion administrateur';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/css/admin-login.css">
</head>
<body>

<div class="login-page">
    <div class="login-card">

        <header class="login-header">
            <div class="login-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h1>Accès administrateur</h1>
            <p class="login-subtitle">Espace réservé au personnel autorisé</p>
        </header>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="login-form" autocomplete="off">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">

            <div class="form-row">
                <label for="email">Adresse email</label>
                <input type="email"
                       id="email"
                       name="email"
                       required
                       autofocus
                       autocomplete="username"
                       value="<?= htmlspecialchars($oldEmail) ?>"
                       placeholder="admin@creai-vbg.org">
            </div>

            <div class="form-row">
                <label for="password">Mot de passe</label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••••••">
            </div>

            <button type="submit" class="btn-login">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Se connecter
            </button>
        </form>

        <footer class="login-footer">
            <p>Accès strictement réservé. Toute connexion est enregistrée.</p>
        </footer>

    </div>
</div>

</body>
</html>