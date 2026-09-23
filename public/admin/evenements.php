<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Evenement.php';

$evenementModel = new Evenement();
$evenements     = $evenementModel->getAll();

$errors  = [];
$success = false;

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function makeSlugEvent(string $text): string
{
    $slug = strtolower(trim($text));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug ?: 'evenement-' . time();
}

// Normalise une date au format MySQL
function normalizeDate(?string $date): ?string
{
    if (!$date) return null;
    $date = str_replace('T', ' ', $date);
    if (strlen($date) === 16) $date .= ':00';
    return $date;
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
            $ev = $evenementModel->find($id);
            if ($ev && !empty($ev['image']) && file_exists(__DIR__ . '/..' . $ev['image'])) {
                @unlink(__DIR__ . '/..' . $ev['image']);
            }
            $evenementModel->delete($id);
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

    $titre         = trim($_POST['titre'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $contenu       = trim($_POST['contenu'] ?? '');
    $lieu          = trim($_POST['lieu'] ?? '');
    $dateDebut     = normalizeDate($_POST['date_debut'] ?? null);
    $dateFin       = normalizeDate($_POST['date_fin'] ?? null);
    $organisateur  = trim($_POST['organisateur'] ?? '');
    $statut        = $_POST['statut'] ?? 'brouillon';
    $image         = null;

    if ($titre === '')       $errors[] = 'Le titre est obligatoire.';
    if ($description === '') $errors[] = 'La description est obligatoire.';
    if ($lieu === '')        $errors[] = 'Le lieu est obligatoire.';
    if (!$dateDebut)         $errors[] = 'La date de début est obligatoire.';
    if (!in_array($statut, ['brouillon', 'publie'], true)) {
        $errors[] = 'Statut invalide.';
    }

    // Upload image
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 3 * 1024 * 1024;

        if (!in_array($_FILES['image']['type'], $allowed, true)) {
            $errors[] = 'Format d\'image non autorisé.';
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $errors[] = 'Image trop lourde (3 Mo max).';
        } else {
            $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dir      = __DIR__ . '/../images/evenements';

            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . '/' . $filename)) {
                $image = '/images/evenements/' . $filename;
            } else {
                $errors[] = 'Échec de l\'upload.';
            }
        }
    }

    if (empty($errors)) {
        $evenementModel->create([
            'titre'        => $titre,
            'slug'         => makeSlugEvent($titre),
            'description'  => $description,
            'contenu'      => $contenu,
            'image'        => $image,
            'lieu'         => $lieu,
            'date_debut'   => $dateDebut,
            'date_fin'     => $dateFin,
            'organisateur' => $organisateur,
            'statut'       => $statut,
        ]);

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

    $id            = (int) ($_POST['id'] ?? 0);
    $titre         = trim($_POST['titre'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $contenu       = trim($_POST['contenu'] ?? '');
    $lieu          = trim($_POST['lieu'] ?? '');
    $dateDebut     = normalizeDate($_POST['date_debut'] ?? null);
    $dateFin       = normalizeDate($_POST['date_fin'] ?? null);
    $organisateur  = trim($_POST['organisateur'] ?? '');
    $statut        = $_POST['statut'] ?? 'brouillon';
    $currentImage  = $_POST['current_image'] ?? null;
    $image         = $currentImage;

    if ($id <= 0)            $errors[] = 'Identifiant invalide.';
    if ($titre === '')       $errors[] = 'Le titre est obligatoire.';
    if ($description === '') $errors[] = 'La description est obligatoire.';
    if ($lieu === '')        $errors[] = 'Le lieu est obligatoire.';
    if (!$dateDebut)         $errors[] = 'La date de début est obligatoire.';

    // Upload nouvelle image
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 3 * 1024 * 1024;

        if (!in_array($_FILES['image']['type'], $allowed, true)) {
            $errors[] = 'Format d\'image non autorisé.';
        } elseif ($_FILES['image']['size'] > $maxSize) {
            $errors[] = 'Image trop lourde (3 Mo max).';
        } else {
            $ext      = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'event_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $dir      = __DIR__ . '/../images/evenements';

            if (!is_dir($dir)) mkdir($dir, 0755, true);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dir . '/' . $filename)) {
                if ($currentImage && file_exists(__DIR__ . '/..' . $currentImage)) {
                    @unlink(__DIR__ . '/..' . $currentImage);
                }
                $image = '/images/evenements/' . $filename;
            } else {
                $errors[] = 'Échec de l\'upload.';
            }
        }
    }

    if (empty($errors)) {
        $evenementModel->update($id, [
            'titre'        => $titre,
            'slug'         => makeSlugEvent($titre),
            'description'  => $description,
            'contenu'      => $contenu,
            'image'        => $image,
            'lieu'         => $lieu,
            'date_debut'   => $dateDebut,
            'date_fin'     => $dateFin,
            'organisateur' => $organisateur,
            'statut'       => $statut,
        ]);

        $success = true;
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
}

$pageTitle = 'Événements';
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
    <h2>Nouvel événement</h2>

    <form method="post" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="create">

        <div class="admin-form-grid">

            <div class="admin-form-row admin-form-row--full">
                <label for="titre">Titre <span class="req">*</span></label>
                <input type="text" id="titre" name="titre" required maxlength="200"
                       placeholder="Ex : Conférence nationale sur les VBG">
            </div>

            <div class="admin-form-row">
                <label for="lieu">Lieu <span class="req">*</span></label>
                <input type="text" id="lieu" name="lieu" required maxlength="200"
                       placeholder="Ex : Yaoundé, Cameroun">
            </div>

            <div class="admin-form-row">
                <label for="organisateur">Organisateur</label>
                <input type="text" id="organisateur" name="organisateur" maxlength="150"
                       placeholder="Ex : CREAI-VBG">
            </div>

            <div class="admin-form-row">
                <label for="date_debut">Date et heure de début <span class="req">*</span></label>
                <input type="datetime-local" id="date_debut" name="date_debut" required>
            </div>

            <div class="admin-form-row">
                <label for="date_fin">Date et heure de fin</label>
                <input type="datetime-local" id="date_fin" name="date_fin">
                <small>Optionnel.</small>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="description">Description <span class="req">*</span></label>
                <textarea id="description" name="description" rows="3" required maxlength="500"
                          placeholder="Résumé court (2-3 phrases) qui apparaîtra dans la liste."></textarea>
                <small>500 caractères max.</small>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="contenu">Contenu détaillé</label>
                <textarea id="contenu" name="contenu" rows="8"
                          placeholder="Programme, intervenants, informations pratiques..."></textarea>
                <small>Optionnel. HTML simple autorisé.</small>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="image">Image de couverture</label>
                <div class="file-input-wrapper">
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                    <span class="file-input-hint">JPG, PNG ou WebP · 3 Mo max · Format 16:10 recommandé</span>
                </div>
            </div>

            <div class="admin-form-row">
                <label for="statut">Statut <span class="req">*</span></label>
                <select id="statut" name="statut" required>
                    <option value="brouillon">Brouillon</option>
                    <option value="publie">Publié</option>
                </select>
            </div>

        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn-admin">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Créer l'événement
            </button>
        </div>
    </form>
</section>

<!-- ============================================================
     LISTE DES ÉVÉNEMENTS
     ============================================================ -->
<section class="admin-card">
    <h2>Événements enregistrés (<?= count($evenements) ?>)</h2>

    <?php if (empty($evenements)): ?>
        <p class="admin-empty">Aucun événement pour le moment.</p>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($evenements as $e): ?>
                        <tr>
                            <td>
                                <img class="admin-thumb"
                                     src="<?= htmlspecialchars($e['image'] ?: '/images/evenements/default.jpg') ?>"
                                     alt="">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($e['titre']) ?></strong>
                                <br>
                                <small class="admin-text-muted">
                                    <?= htmlspecialchars(mb_substr($e['description'], 0, 70)) ?>…
                                </small>
                            </td>
                            <td><?= htmlspecialchars($e['lieu']) ?></td>
                            <td>
                                <?= htmlspecialchars(date('d/m/Y H:i', strtotime($e['date_debut']))) ?>
                                <?php if (!empty($e['date_fin'])): ?>
                                    <br>
                                    <small class="admin-text-muted">
                                        → <?= htmlspecialchars(date('d/m/Y H:i', strtotime($e['date_fin']))) ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($e['statut'] === 'publie'): ?>
                                    <span class="admin-badge admin-badge--success">Publié</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge--warning">Brouillon</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-actions">
                                <div class="action-buttons">
                                    <button type="button"
                                            class="btn-action btn-action--edit"
                                            data-edit
                                            data-id="<?= (int) $e['id'] ?>"
                                            data-titre="<?= htmlspecialchars($e['titre']) ?>"
                                            data-description="<?= htmlspecialchars($e['description']) ?>"
                                            data-contenu="<?= htmlspecialchars($e['contenu'] ?? '') ?>"
                                            data-lieu="<?= htmlspecialchars($e['lieu']) ?>"
                                            data-date-debut="<?= htmlspecialchars($e['date_debut']) ?>"
                                            data-date-fin="<?= htmlspecialchars($e['date_fin'] ?? '') ?>"
                                            data-organisateur="<?= htmlspecialchars($e['organisateur'] ?? '') ?>"
                                            data-statut="<?= htmlspecialchars($e['statut']) ?>"
                                            data-image="<?= htmlspecialchars($e['image'] ?? '') ?>"
                                            aria-label="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Modifier
                                    </button>

                                    <form method="post" data-confirm="Supprimer cet événement ?" class="form-inline">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $e['id'] ?>">
                                        <button type="submit" class="btn-action btn-action--delete">
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
    <div class="modal-content modal-content--wide" role="dialog" aria-labelledby="modalTitle">
        <header class="modal-header">
            <h2 id="modalTitle">Modifier l'événement</h2>
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
            <input type="hidden" name="current_image" id="edit_current_image">

            <div class="admin-form-grid">

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_titre">Titre <span class="req">*</span></label>
                    <input type="text" id="edit_titre" name="titre" required maxlength="200">
                </div>

                <div class="admin-form-row">
                    <label for="edit_lieu">Lieu <span class="req">*</span></label>
                    <input type="text" id="edit_lieu" name="lieu" required maxlength="200">
                </div>

                <div class="admin-form-row">
                    <label for="edit_organisateur">Organisateur</label>
                    <input type="text" id="edit_organisateur" name="organisateur" maxlength="150">
                </div>

                <div class="admin-form-row">
                    <label for="edit_date_debut">Date et heure de début <span class="req">*</span></label>
                    <input type="datetime-local" id="edit_date_debut" name="date_debut" required>
                </div>

                <div class="admin-form-row">
                    <label for="edit_date_fin">Date et heure de fin</label>
                    <input type="datetime-local" id="edit_date_fin" name="date_fin">
                </div>

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_description">Description <span class="req">*</span></label>
                    <textarea id="edit_description" name="description" rows="3" required maxlength="500"></textarea>
                </div>

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_contenu">Contenu détaillé</label>
                    <textarea id="edit_contenu" name="contenu" rows="8"></textarea>
                </div>

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_image">Nouvelle image (laisser vide pour conserver)</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="edit_image" name="image" accept="image/jpeg,image/png,image/webp">
                        <span class="file-input-hint">JPG, PNG ou WebP · 3 Mo max</span>
                    </div>
                </div>

                <div class="admin-form-row">
                    <label for="edit_statut">Statut <span class="req">*</span></label>
                    <select id="edit_statut" name="statut" required>
                        <option value="brouillon">Brouillon</option>
                        <option value="publie">Publié</option>
                    </select>
                </div>

            </div>

            <div class="modal-actions">
                <button type="button" class="btn-admin btn-admin--ghost" id="modalCancel">Annuler</button>
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

<script src="/js/admin-evenements.js" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>