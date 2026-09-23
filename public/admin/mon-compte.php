<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Admin.php';

$model  = new Admin();
$moiId  = (int) $currentAdmin['id'];
$moi    = $model->find($moiId);
$forced = AdminAuth::mustChangePassword();

if (!$moi) {
    AdminAuth::logout();
    header('Location: /login-admin.php');
    exit;
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current = (string) ($_POST['current_password'] ?? '');
    $new     = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['password_confirm'] ?? '');

    // Limitation des essais : au plus 5 mauvais « mot de passe actuel » sur 10 minutes
    $now   = time();
    $fails = array_values(array_filter((array) ($_SESSION['pw_fails'] ?? []), static fn ($t) => is_int($t) && $now - $t < 600));

    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) {
        $errors[] = 'Jeton de sécurité invalide : rechargez la page puis réessayez.';
    } elseif (count($fails) >= 5) {
        $errors[] = 'Trop d\'essais incorrects. Patientez 10 minutes avant de réessayer.';
    } else {
        $hash = $model->passwordHash($moiId);

        if ($hash === null || !password_verify($current, $hash)) {
            $fails[] = $now;
            $_SESSION['pw_fails'] = $fails;
            usleep(300000);
            $errors[] = 'Votre mot de passe actuel est incorrect.';
        } elseif ($new !== $confirm) {
            $errors[] = 'Les deux nouveaux mots de passe ne sont pas identiques.';
        } elseif ($new === $current) {
            $errors[] = 'Le nouveau mot de passe doit être différent de l\'ancien.';
        } elseif (($pwdError = Admin::validatePassword($new, $moi['email'], $moi['nom'])) !== null) {
            $errors[] = $pwdError;
        } else {
            $model->setPassword($moiId, Admin::hashPassword($new), false);

            unset($_SESSION['pw_fails']);
            $_SESSION['admin_must_change'] = 0;
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            session_regenerate_id(true);

            $_SESSION['flash_compte'] = 'Votre mot de passe a été modifié.';
            header('Location: ' . ($forced ? '/admin/dashboard.php' : '/admin/mon-compte.php'));
            exit;
        }
    }
}

$flash = $_SESSION['flash_compte'] ?? null;
unset($_SESSION['flash_compte']);

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

$pageTitle = 'Mon compte';
$extraJs   = ['/js/admin-password.js'];
require __DIR__ . '/includes/header.php';
?>

<?php if ($forced): ?>
    <div class="alert alert-error" role="alert">
        <strong>Bienvenue.</strong> Votre compte a été créé avec un mot de passe temporaire :
        choisissez maintenant <strong>votre propre mot de passe</strong> pour accéder à l'administration.
    </div>
<?php endif; ?>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= $e($flash) ?></div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <ul><?php foreach ($errors as $err): ?><li><?= $e($err) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<section class="admin-card">
    <h2>Mes informations</h2>
    <dl class="adhesion-meta admin-account-meta">
        <div><dt>Nom</dt><dd><?= $e($moi['nom']) ?></dd></div>
        <div><dt>Email (identifiant)</dt><dd><?= $e($moi['email']) ?></dd></div>
        <div><dt>Rôle</dt><dd><?= $e(Admin::ROLES[$moi['role']] ?? $moi['role']) ?></dd></div>
        <div>
            <dt>Dernière connexion</dt>
            <dd><?= $moi['derniere_connexion'] ? $e(date('d/m/Y à H:i', strtotime($moi['derniere_connexion']))) : 'Première connexion' ?></dd>
        </div>
    </dl>
</section>

<section class="admin-card <?= $forced ? 'admin-card--editing' : '' ?>">
    <h2>Changer mon mot de passe</h2>

    <form method="post" class="admin-form" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">

        <div class="admin-form-grid">
            <div class="admin-form-row admin-form-row--full">
                <label for="current_password">Mot de passe actuel <span class="req">*</span></label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
            </div>

            <div class="admin-form-row">
                <label for="password">Nouveau mot de passe <span class="req">*</span></label>
                <input type="password" id="password" name="password" required minlength="12" maxlength="200" autocomplete="new-password">
            </div>

            <div class="admin-form-row">
                <label for="password_confirm">Confirmer le nouveau mot de passe <span class="req">*</span></label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="12" maxlength="200" autocomplete="new-password">
            </div>

            <div class="admin-form-row admin-form-row--full">
                <div class="admin-pw-tools">
                    <button type="button" class="btn-admin btn-admin--ghost btn-admin--sm" data-pw-generate data-target="password,password_confirm">
                        Générer un mot de passe fort
                    </button>
                    <button type="button" class="btn-admin btn-admin--ghost btn-admin--sm" data-pw-toggle data-target="password,password_confirm,current_password">
                        Afficher / masquer
                    </button>
                    <span class="admin-pw-status" data-pw-status role="status" aria-live="polite"></span>
                </div>
                <small>
                    12 caractères minimum, avec au moins une lettre et un chiffre, sans reprendre votre nom ni votre identifiant.
                    Conservez-le dans un gestionnaire de mots de passe.
                </small>
            </div>
        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn-admin">Enregistrer mon nouveau mot de passe</button>
        </div>
    </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
