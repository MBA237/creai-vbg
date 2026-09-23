<?php
declare(strict_types=1);

// ============================================================
//   PROTECTION
// ============================================================
require __DIR__ . '/includes/auth.php';

// ============================================================
//   DÉPENDANCES
// ============================================================
require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Pole.php';
require_once __DIR__ . '/../../src/Membre.php';

$poleModel   = new Pole();
$membreModel = new Membre();

$poles   = $poleModel->getAll();
$membres = $membreModel->getAll();

$errors  = [];
$success = false;

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// ============================================================
//   SUPPRESSION
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token invalide.';
    } else {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $membreModel->delete($id);
            $success = true;
        }
    }
}

// ============================================================
//   AJOUT
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token invalide.';
    }

    $poleId     = (int) ($_POST['pole_id'] ?? 0);
    $nom        = trim($_POST['nom'] ?? '');
    $profession = trim($_POST['profession'] ?? '');
    $poste      = trim($_POST['poste'] ?? '');
    $ordre      = (int) ($_POST['ordre'] ?? 0);
    $photo      = null;

    if ($nom === '')        $errors[] = 'Le nom est obligatoire.';
    if ($profession === '') $errors[] = 'La profession est obligatoire.';
    if ($poste === '')      $errors[] = 'Le poste est obligatoire.';
    if ($poleId <= 0)       $errors[] = 'Veuillez choisir un pôle.';

    // Upload photo
    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($_FILES['photo']['type'], $allowed, true)) {
            $errors[] = 'Format d\'image non autorisé (jpg, png, webp).';
        } elseif ($_FILES['photo']['size'] > $maxSize) {
            $errors[] = 'Image trop lourde (2 Mo max).';
        } else {
            $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'membre_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dir      = __DIR__ . '/../images/team';

            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dir . '/' . $filename)) {
                $photo = '/images/team/' . $filename;
            } else {
                $errors[] = 'Échec de l\'upload.';
            }
        }
    }

    if (empty($errors)) {
        $membreModel->create($poleId, $nom, $profession, $poste, $photo, $ordre);
        $success = true;
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
}

// ============================================================
//   MODIFICATION
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        $errors[] = 'Token invalide.';
    }

    $id         = (int) ($_POST['id'] ?? 0);
    $poleId     = (int) ($_POST['pole_id'] ?? 0);
    $nom        = trim($_POST['nom'] ?? '');
    $profession = trim($_POST['profession'] ?? '');
    $poste      = trim($_POST['poste'] ?? '');
    $ordre      = (int) ($_POST['ordre'] ?? 0);
    $currentPhoto = $_POST['current_photo'] ?? null;
    $photo      = $currentPhoto;

    if ($id <= 0)           $errors[] = 'Identifiant invalide.';
    if ($nom === '')        $errors[] = 'Le nom est obligatoire.';
    if ($profession === '') $errors[] = 'La profession est obligatoire.';
    if ($poste === '')      $errors[] = 'Le poste est obligatoire.';
    if ($poleId <= 0)       $errors[] = 'Veuillez choisir un pôle.';

    // Upload nouvelle photo
    if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($_FILES['photo']['type'], $allowed, true)) {
            $errors[] = 'Format d\'image non autorisé (jpg, png, webp).';
        } elseif ($_FILES['photo']['size'] > $maxSize) {
            $errors[] = 'Image trop lourde (2 Mo max).';
        } else {
            $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'membre_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dir      = __DIR__ . '/../images/team';

            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dir . '/' . $filename)) {
                // Supprime l'ancienne photo si elle existe
                if ($currentPhoto && file_exists(__DIR__ . '/..' . $currentPhoto)) {
                    @unlink(__DIR__ . '/..' . $currentPhoto);
                }
                $photo = '/images/team/' . $filename;
            } else {
                $errors[] = 'Échec de l\'upload.';
            }
        }
    }

    if (empty($errors)) {
        $membreModel->update($id, $poleId, $nom, $profession, $poste, $photo, $ordre);
        $success = true;
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
}

// ============================================================
//   HEADER ADMIN
// ============================================================
$pageTitle = 'Membres de l\'équipe';
require __DIR__ . '/includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert alert-success">Opération réussie.</div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<!-- ============================================================
     FORMULAIRE D'AJOUT
     ============================================================ -->
<section class="admin-card">
    <h2>Ajouter un membre</h2>

    <form method="post" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="create">

        <div class="admin-form-grid">

            <div class="admin-form-row">
                <label for="nom">Nom complet <span class="req">*</span></label>
                <input type="text" id="nom" name="nom" required maxlength="100"
                       placeholder="Ex : Marie Ngo Bassong">
            </div>

            <div class="admin-form-row">
                <label for="pole_id">Pôle <span class="req">*</span></label>
                <select id="pole_id" name="pole_id" required>
                    <option value="">— Choisir un pôle —</option>
                    <?php foreach ($poles as $p): ?>
                        <option value="<?= (int) $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="admin-form-row">
                <label for="poste">Poste <span class="req">*</span></label>
                <input type="text" id="poste" name="poste" required maxlength="150"
                       placeholder="Ex : Président, Coordinatrice...">
            </div>

            <div class="admin-form-row">
                <label for="profession">Profession <span class="req">*</span></label>
                <input type="text" id="profession" name="profession" required maxlength="150"
                       placeholder="Ex : Juriste, Psychologue...">
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="photo">Photo de profil</label>
                <div class="file-input-wrapper">
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
                    <span class="file-input-hint">JPG, PNG ou WebP · 2 Mo max</span>
                </div>
            </div>

            <div class="admin-form-row">
                <label for="ordre">Ordre d'affichage</label>
                <input type="number" id="ordre" name="ordre" value="0" min="0">
                <small>Plus le chiffre est petit, plus le membre apparaît en premier.</small>
            </div>

        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn-admin">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Ajouter le membre
            </button>
        </div>
    </form>
</section>

<!-- ============================================================
     LISTE DES MEMBRES
     ============================================================ -->
<section class="admin-card">
    <h2>Membres enregistrés (<?= count($membres) ?>)</h2>

    <?php if (empty($membres)): ?>
        <p class="admin-empty">Aucun membre enregistré pour le moment.</p>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Pôle</th>
                        <th>Poste</th>
                        <th>Profession</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($membres as $m): ?>
                        <tr>
                            <td>
                                <img class="admin-avatar"
                                     src="<?= htmlspecialchars($m['photo'] ?: '/images/team/default-avatar.png') ?>"
                                     alt="">
                            </td>
                            <td><strong><?= htmlspecialchars($m['nom']) ?></strong></td>
                            <td>
                                <span class="admin-badge">
                                    <?= htmlspecialchars($m['pole_nom']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($m['poste']) ?></td>
                            <td><em><?= htmlspecialchars($m['profession']) ?></em></td>
                            <td class="td-actions">
                                <div class="action-buttons">
                                    <button type="button"
                                            class="btn-action btn-action--edit"
                                            data-edit
                                            data-id="<?= (int) $m['id'] ?>"
                                            data-pole-id="<?= (int) $m['pole_id'] ?>"
                                            data-nom="<?= htmlspecialchars($m['nom']) ?>"
                                            data-poste="<?= htmlspecialchars($m['poste']) ?>"
                                            data-profession="<?= htmlspecialchars($m['profession']) ?>"
                                            data-photo="<?= htmlspecialchars($m['photo'] ?? '') ?>"
                                            data-ordre="<?= (int) $m['ordre'] ?>"
                                            aria-label="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Modifier
                                    </button>

                                    <form method="post" data-confirm="Supprimer ce membre ?" class="form-inline">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
                                        <button type="submit" class="btn-action btn-action--delete" aria-label="Supprimer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                <path d="M10 11v6M14 11v6"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<!-- ============================================================
     MODAL DE MODIFICATION
     ============================================================ -->
<div class="modal-overlay" id="modalEdit" hidden>
    <div class="modal-content" role="dialog" aria-labelledby="modalTitle">
        <header class="modal-header">
            <h2 id="modalTitle">Modifier le membre</h2>
            <button type="button" class="modal-close" id="modalClose" aria-label="Fermer">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </header>

        <form method="post" enctype="multipart/form-data" class="admin-form modal-form">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">
            <input type="hidden" name="current_photo" id="edit_current_photo">

            <div class="admin-form-grid">

                <div class="admin-form-row">
                    <label for="edit_nom">Nom complet <span class="req">*</span></label>
                    <input type="text" id="edit_nom" name="nom" required maxlength="100">
                </div>

                <div class="admin-form-row">
                    <label for="edit_pole_id">Pôle <span class="req">*</span></label>
                    <select id="edit_pole_id" name="pole_id" required>
                        <?php foreach ($poles as $p): ?>
                            <option value="<?= (int) $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="admin-form-row">
                    <label for="edit_poste">Poste <span class="req">*</span></label>
                    <input type="text" id="edit_poste" name="poste" required maxlength="150">
                </div>

                <div class="admin-form-row">
                    <label for="edit_profession">Profession <span class="req">*</span></label>
                    <input type="text" id="edit_profession" name="profession" required maxlength="150">
                </div>

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_photo">Nouvelle photo (laisser vide pour conserver)</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="edit_photo" name="photo" accept="image/jpeg,image/png,image/webp">
                        <span class="file-input-hint">JPG, PNG ou WebP · 2 Mo max</span>
                    </div>
                </div>

                <div class="admin-form-row">
                    <label for="edit_ordre">Ordre d'affichage</label>
                    <input type="number" id="edit_ordre" name="ordre" min="0">
                </div>

            </div>

            <div class="modal-actions">
                <button type="button" class="btn-admin btn-admin--ghost" id="modalCancel">
                    Annuler
                </button>
                <button type="submit" class="btn-admin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script src="/js/admin-membres.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>