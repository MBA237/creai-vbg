<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Admin.php';

// Gestion des comptes : réservée aux super administrateurs
if (!AdminAuth::hasRole('super_admin')) {
    http_response_code(403);
    $pageTitle = 'Accès réservé';
    require __DIR__ . '/includes/header.php';
    echo '<div class="alert alert-error">La gestion des administrateurs est réservée aux super administrateurs.</div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$model = new Admin();
$moiId = (int) $currentAdmin['id'];

$errors = [];
$old    = [];   // saisie conservée si l'enregistrement échoue
$editId = 0;    // compte en cours de modification (0 = nouveau compte)

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash_admins'] = ['message' => $message, 'type' => $type];
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/** Vrai si on peut retirer ce compte des super administrateurs actifs sans en laisser aucun. */
function canLoseSuperAdmin(Admin $model, array $target): bool
{
    if ($target['role'] !== 'super_admin' || (int) $target['actif'] !== 1) {
        return true; // il ne compte pas parmi les super administrateurs actifs
    }
    return $model->countActiveSuperAdmins((int) $target['id']) >= 1;
}

// ============================================================
//   ACTIONS (POST)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = (string) ($_POST['action'] ?? '');
    $id     = (int) ($_POST['id'] ?? 0);
    $target = $id > 0 ? $model->find($id) : null;

    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) {
        $errors[] = 'Jeton de sécurité invalide : rechargez la page puis réessayez.';

    // ---------------- Désactiver / réactiver ----------------
    } elseif ($action === 'set_actif') {
        $actif = ($_POST['actif'] ?? '') === '1';

        if (!$target) {
            flash('Compte introuvable.', 'error');
        } elseif ($id === $moiId) {
            flash('Vous ne pouvez pas désactiver votre propre compte.', 'error');
        } elseif (!$actif && !canLoseSuperAdmin($model, $target)) {
            flash('Impossible : ce compte est le dernier super administrateur actif.', 'error');
        } else {
            $model->setActif($id, $actif);
            flash($actif
                ? 'Le compte de ' . $target['nom'] . ' est réactivé.'
                : 'Le compte de ' . $target['nom'] . ' est désactivé : cette personne perd l\'accès immédiatement.');
        }
        redirect('/admin/admins.php');

    // ---------------- Débloquer (trop de tentatives) ----------------
    } elseif ($action === 'unblock') {
        if ($target) {
            $model->unblock($id);
            flash('Le compte de ' . $target['nom'] . ' est débloqué.');
        }
        redirect('/admin/admins.php');

    // ---------------- Supprimer ----------------
    } elseif ($action === 'delete') {
        if (!$target) {
            flash('Compte introuvable.', 'error');
        } elseif ($id === $moiId) {
            flash('Vous ne pouvez pas supprimer votre propre compte.', 'error');
        } elseif (!canLoseSuperAdmin($model, $target)) {
            flash('Impossible : ce compte est le dernier super administrateur actif.', 'error');
        } else {
            $model->delete($id);
            flash('Le compte de ' . $target['nom'] . ' est supprimé.');
        }
        redirect('/admin/admins.php');

    // ---------------- Création / modification ----------------
    } elseif ($action === 'create' || $action === 'update') {

        $editing = $action === 'update';
        if ($editing && !$target) {
            $errors[] = 'Compte introuvable.';
        }

        $nom      = trim((string) ($_POST['nom'] ?? ''));
        $email    = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
        $role     = (string) ($_POST['role'] ?? '');
        $actif    = !empty($_POST['actif']);
        $password = (string) ($_POST['password'] ?? '');
        $confirm  = (string) ($_POST['password_confirm'] ?? '');

        // Sur son propre compte : ni rôle, ni statut modifiables (évite de se retirer l'accès)
        if ($editing && $target && $id === $moiId) {
            $role  = $target['role'];
            $actif = true;
        }
        // Un compte qu'on crée est toujours actif
        if (!$editing) {
            $actif = true;
        }

        if ($nom === '' || mb_strlen($nom) > 100)                  $errors[] = 'Le nom est obligatoire (100 caractères maximum).';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 255) $errors[] = 'L\'adresse email n\'est pas valide.';
        if (!isset(Admin::ROLES[$role]))                           $errors[] = 'Choisissez un rôle dans la liste.';

        if (empty($errors) && $model->emailExists($email, $editing ? $id : 0)) {
            $errors[] = 'Un compte utilise déjà cette adresse email.';
        }

        // Mot de passe : obligatoire à la création, facultatif à la modification
        $newHash = null;
        if (!$editing || $password !== '' || $confirm !== '') {
            if ($password !== $confirm) {
                $errors[] = 'Les deux mots de passe ne sont pas identiques.';
            } elseif (($pwdError = Admin::validatePassword($password, $email, $nom)) !== null) {
                $errors[] = $pwdError;
            } else {
                $newHash = Admin::hashPassword($password);
            }
        }

        // Ne jamais retirer le dernier super administrateur actif
        if ($editing && $target && empty($errors)) {
            $reste = ($role === 'super_admin' && $actif);
            if (!$reste && !canLoseSuperAdmin($model, $target)) {
                $errors[] = 'Impossible : ce compte est le dernier super administrateur actif, son rôle et son statut ne peuvent pas être retirés.';
            }
        }

        $old = ['nom' => $nom, 'email' => $email, 'role' => $role, 'actif' => $actif ? '1' : ''];
        $editId = $editing ? $id : 0;

        if (empty($errors)) {
            try {
                if ($editing) {
                    $model->update($id, $nom, $email, $role, $actif);
                    if ($newHash !== null) {
                        // Le titulaire choisira son propre mot de passe à sa prochaine connexion
                        $model->setPassword($id, $newHash, $id !== $moiId);
                    }
                    flash('Le compte de ' . $nom . ' est mis à jour' . ($newHash !== null && $id !== $moiId ? ' (nouveau mot de passe temporaire défini).' : '.'));
                } else {
                    $model->create($nom, $email, $role, $newHash);
                    flash('Compte créé pour ' . $nom . '. Transmettez-lui son mot de passe temporaire de façon sécurisée : il devra en choisir un nouveau à sa première connexion.');
                }
                $_SESSION['csrf'] = bin2hex(random_bytes(32));
                redirect('/admin/admins.php');
            } catch (mysqli_sql_exception $e) {
                error_log('[admins.php] ' . $e->getMessage());
                $errors[] = 'Le compte n\'a pas pu être enregistré (problème technique).';
            }
        }
    }
}

// ============================================================
//   AFFICHAGE
// ============================================================

$flash = $_SESSION['flash_admins'] ?? null;
unset($_SESSION['flash_admins']);

if ($editId === 0) {
    $editId = (int) ($_GET['edit'] ?? 0);
}

$editing = null;
if ($editId > 0) {
    $editing = $model->find($editId);
    if (!$editing) {
        flash('Ce compte n\'existe plus.', 'error');
        redirect('/admin/admins.php');
    }
}

$form = ['nom' => '', 'email' => '', 'role' => 'editeur', 'actif' => '1'];
if ($editing) {
    $form = ['nom' => $editing['nom'], 'email' => $editing['email'], 'role' => $editing['role'], 'actif' => (int) $editing['actif'] === 1 ? '1' : ''];
}
if ($old) {
    $form = array_merge($form, $old);
}

$estMoi = $editing && (int) $editing['id'] === $moiId;
$admins = $model->getAll();

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

$pageTitle = 'Administrateurs';
$extraJs   = ['/js/admin-password.js'];
require __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
        <?= $e($flash['message']) ?>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <ul><?php foreach ($errors as $err): ?><li><?= $e($err) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<!-- ============================================================
     FORMULAIRE : ajout ET modification d'un compte (au même endroit)
     ============================================================ -->
<section class="admin-card <?= $editing ? 'admin-card--editing' : '' ?>" id="admin-form">

    <div class="admin-card-head">
        <h2><?= $editing ? 'Modifier le compte de ' . $e($editing['nom']) : 'Ajouter un administrateur' ?></h2>
    </div>

    <form method="post" class="admin-form" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>">
        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= (int) $editing['id'] ?>">
        <?php endif; ?>

        <div class="admin-form-grid">

            <div class="admin-form-row">
                <label for="nom">Nom complet <span class="req">*</span></label>
                <input type="text" id="nom" name="nom" required maxlength="100"
                       value="<?= $e($form['nom']) ?>" placeholder="Ex : Marie Ngo Bassong">
            </div>

            <div class="admin-form-row">
                <label for="email">Adresse email (identifiant de connexion) <span class="req">*</span></label>
                <input type="email" id="email" name="email" required maxlength="255"
                       value="<?= $e($form['email']) ?>" placeholder="prenom.nom@exemple.org" autocomplete="off">
            </div>

            <div class="admin-form-row admin-form-row--full">
                <span class="admin-label">Rôle <span class="req">*</span></span>
                <div class="admin-roles" role="radiogroup" aria-label="Rôle">
                    <?php foreach (Admin::ROLES as $value => $label): ?>
                        <label class="admin-role <?= $estMoi ? 'is-disabled' : '' ?>">
                            <input type="radio" name="role" value="<?= $e($value) ?>"
                                   <?= $form['role'] === $value ? 'checked' : '' ?>
                                   <?= $estMoi ? 'disabled' : '' ?>>
                            <span>
                                <strong><?= $e($label) ?></strong>
                                <small><?= $e(Admin::ROLE_DESCRIPTIONS[$value]) ?></small>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?php if ($estMoi): ?>
                    <small>Vous ne pouvez pas modifier votre propre rôle ni désactiver votre propre compte.</small>
                <?php endif; ?>
            </div>

            <?php if ($editing && !$estMoi): ?>
                <div class="admin-form-row admin-form-row--full">
                    <div class="admin-checks">
                        <label class="admin-check">
                            <input type="checkbox" name="actif" value="1" <?= $form['actif'] === '1' ? 'checked' : '' ?>>
                            Compte actif (décochez pour retirer l'accès sans supprimer le compte)
                        </label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="admin-form-row admin-form-row--full">
                <span class="admin-label">
                    <?= $editing ? 'Nouveau mot de passe' : 'Mot de passe temporaire' ?>
                    <?php if (!$editing): ?><span class="req">*</span><?php endif; ?>
                </span>
                <div class="admin-pw-grid">
                    <div>
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" minlength="12" maxlength="200"
                               autocomplete="new-password" <?= $editing ? '' : 'required' ?>>
                    </div>
                    <div>
                        <label for="password_confirm">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirm" name="password_confirm" minlength="12" maxlength="200"
                               autocomplete="new-password" <?= $editing ? '' : 'required' ?>>
                    </div>
                </div>
                <div class="admin-pw-tools">
                    <button type="button" class="btn-admin btn-admin--ghost btn-admin--sm" data-pw-generate data-target="password,password_confirm">
                        Générer un mot de passe fort
                    </button>
                    <button type="button" class="btn-admin btn-admin--ghost btn-admin--sm" data-pw-toggle data-target="password,password_confirm">
                        Afficher / masquer
                    </button>
                    <span class="admin-pw-status" data-pw-status role="status" aria-live="polite"></span>
                </div>
                <small>
                    12 caractères minimum, avec au moins une lettre et un chiffre.
                    <?= $editing ? 'Laissez vide pour ne pas changer le mot de passe. ' : '' ?>
                    <?php if (!$estMoi): ?>Ce mot de passe est <strong>temporaire</strong> : la personne devra en choisir un nouveau dès sa première connexion.<?php endif; ?>
                </small>
            </div>

        </div>

        <div class="admin-form-actions">
            <?php if ($editing): ?>
                <a href="/admin/admins.php" class="btn-admin btn-admin--ghost">Annuler</a>
            <?php endif; ?>
            <button type="submit" class="btn-admin">
                <?= $editing ? 'Enregistrer les modifications' : 'Créer le compte' ?>
            </button>
        </div>
    </form>
</section>

<!-- ============================================================
     LISTE DES COMPTES
     ============================================================ -->
<section class="admin-card">
    <h2>Comptes administrateurs (<?= count($admins) ?>)</h2>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Compte</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Dernière connexion</th>
                    <th class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $a):
                    $moi    = (int) $a['id'] === $moiId;
                    $actif  = (int) $a['actif'] === 1;
                    $bloque = $a['bloque_jusqua'] && strtotime($a['bloque_jusqua']) > time();
                ?>
                    <tr class="<?= $editing && (int) $editing['id'] === (int) $a['id'] ? 'is-editing' : '' ?>">
                        <td>
                            <strong><?= $e($a['nom']) ?></strong>
                            <?php if ($moi): ?><span class="admin-badge admin-badge--info">Vous</span><?php endif; ?>
                            <br>
                            <small class="admin-text-muted"><?= $e($a['email']) ?></small>
                        </td>
                        <td><span class="admin-badge"><?= $e(Admin::ROLES[$a['role']] ?? $a['role']) ?></span></td>
                        <td>
                            <?php if (!$actif): ?>
                                <span class="admin-badge admin-badge--muted">Désactivé</span>
                            <?php elseif ($bloque): ?>
                                <span class="admin-badge admin-badge--danger">Bloqué</span>
                            <?php else: ?>
                                <span class="admin-badge admin-badge--success">Actif</span>
                            <?php endif; ?>
                            <?php if ((int) $a['doit_changer_mdp'] === 1): ?>
                                <br><small class="admin-text-muted">Mot de passe à changer</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($a['derniere_connexion']): ?>
                                <?= $e(date('d/m/Y H:i', strtotime($a['derniere_connexion']))) ?>
                            <?php else: ?>
                                <span class="admin-text-muted">Jamais</span>
                            <?php endif; ?>
                        </td>
                        <td class="td-actions">
                            <div class="action-buttons">

                                <a href="/admin/admins.php?edit=<?= (int) $a['id'] ?>#admin-form" class="btn-action btn-action--edit">Modifier</a>

                                <?php if ($bloque): ?>
                                    <form method="post" class="form-inline">
                                        <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="unblock">
                                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                                        <button type="submit" class="btn-action btn-action--publish">Débloquer</button>
                                    </form>
                                <?php endif; ?>

                                <?php if (!$moi): ?>
                                    <form method="post" class="form-inline"
                                          <?= $actif ? 'data-confirm="Désactiver ce compte ? La personne perdra l\'accès immédiatement."' : '' ?>>
                                        <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="set_actif">
                                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                                        <input type="hidden" name="actif" value="<?= $actif ? '0' : '1' ?>">
                                        <button type="submit" class="btn-action <?= $actif ? 'btn-action--unpublish' : 'btn-action--publish' ?>">
                                            <?= $actif ? 'Désactiver' : 'Réactiver' ?>
                                        </button>
                                    </form>

                                    <form method="post" class="form-inline" data-confirm="Supprimer définitivement le compte de <?= $e($a['nom']) ?> ? Cette action est irréversible.">
                                        <input type="hidden" name="csrf" value="<?= $e($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                                        <button type="submit" class="btn-action btn-action--delete">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
