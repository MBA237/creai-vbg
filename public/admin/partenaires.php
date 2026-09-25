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
require_once __DIR__ . '/../../src/Partenaire.php';
require_once __DIR__ . '/../../src/ImageUpload.php';

$partenaireModel = new Partenaire();

$errors  = [];
$success = '';
$old     = [];   // saisie conservée quand l'ajout échoue

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/** Supprime un logo du disque, uniquement s'il est dans le dossier des logos de partenaires. */
function deleteLogo(?string $url): void
{
    if (!$url || !str_starts_with($url, '/images/partenaires/') || str_contains($url, '..')) {
        return;
    }
    $path = __DIR__ . '/..' . $url;
    if (is_file($path)) {
        @unlink($path);
    }
}

/** Champs texte communs à l'ajout et à la modification. */
function readPartenaireFields(): array
{
    return [
        'nom'         => trim((string) ($_POST['nom'] ?? '')),
        'categorie'   => trim((string) ($_POST['categorie'] ?? '')),
        'description' => trim((string) ($_POST['description'] ?? '')),
        'site_web'    => trim((string) ($_POST['site_web'] ?? '')),
        'ordre'       => max(0, (int) ($_POST['ordre'] ?? 0)),
        'statut'      => (string) ($_POST['statut'] ?? 'publie'),
    ];
}

/** @return string[] messages d'erreur */
function validatePartenaire(array $f): array
{
    $errors = [];
    if ($f['nom'] === '') {
        $errors[] = 'Le nom du partenaire est obligatoire.';
    } elseif (mb_strlen($f['nom']) > 150) {
        $errors[] = 'Le nom est trop long (150 caractères max).';
    }
    if (mb_strlen($f['categorie']) > 100) {
        $errors[] = 'La catégorie est trop longue (100 caractères max).';
    }
    if (mb_strlen($f['description']) > 1000) {
        $errors[] = 'La description est trop longue (1 000 caractères max).';
    }
    if ($f['site_web'] !== '' && Partenaire::normalizeUrl($f['site_web']) === null) {
        $errors[] = 'L\'adresse du site web n\'est pas valide (ex. https://www.exemple.org).';
    }
    if (!in_array($f['statut'], Partenaire::STATUTS, true)) {
        $errors[] = 'Statut invalide.';
    }
    return $errors;
}

$csrfOk = $_SERVER['REQUEST_METHOD'] === 'POST'
    && hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''));
$action = $_SERVER['REQUEST_METHOD'] === 'POST' ? (string) ($_POST['action'] ?? '') : '';

if ($action !== '' && !$csrfOk) {
    $errors[] = 'Session expirée : veuillez recharger la page et réessayer.';
    $action = '';
}

// ============================================================
//   SUPPRESSION
// ============================================================
if ($action === 'delete') {
    $id      = (int) ($_POST['id'] ?? 0);
    $current = $id > 0 ? $partenaireModel->find($id) : null;
    if ($current) {
        $partenaireModel->delete($id);
        deleteLogo($current['logo']);
        $success = 'Partenaire supprimé.';
    }
}

// ============================================================
//   AJOUT
// ============================================================
if ($action === 'create') {
    $fields = readPartenaireFields();
    $errors = validatePartenaire($fields);
    $logo   = null;

    if (empty($errors) && !empty($_FILES['logo']['name'])) {
        $up = ImageUpload::store($_FILES['logo'], __DIR__ . '/../images/partenaires', '/images/partenaires', 'partenaire', 2 * 1024 * 1024);
        if (isset($up['error'])) {
            $errors[] = $up['error'];
        } else {
            $logo = $up['url'];
        }
    }

    if (empty($errors)) {
        $partenaireModel->create([
            'nom'         => $fields['nom'],
            'categorie'   => $fields['categorie'] !== '' ? $fields['categorie'] : null,
            'description' => $fields['description'] !== '' ? $fields['description'] : null,
            'logo'        => $logo,
            'site_web'    => Partenaire::normalizeUrl($fields['site_web']),
            'ordre'       => $fields['ordre'],
            'statut'      => $fields['statut'],
        ]);
        $success = 'Partenaire ajouté.';
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    } else {
        $old = $fields;
    }
}

// ============================================================
//   MODIFICATION
// ============================================================
if ($action === 'update') {
    $id      = (int) ($_POST['id'] ?? 0);
    $current = $id > 0 ? $partenaireModel->find($id) : null;
    $fields  = readPartenaireFields();
    $errors  = validatePartenaire($fields);
    $logo    = $current['logo'] ?? null;

    if (!$current) {
        $errors[] = 'Partenaire introuvable.';
    }

    if (empty($errors) && !empty($_POST['remove_logo'])) {
        $logo = null;
    }

    if (empty($errors) && !empty($_FILES['logo']['name'])) {
        $up = ImageUpload::store($_FILES['logo'], __DIR__ . '/../images/partenaires', '/images/partenaires', 'partenaire', 2 * 1024 * 1024);
        if (isset($up['error'])) {
            $errors[] = $up['error'];
        } else {
            $logo = $up['url'];
        }
    }

    if (empty($errors)) {
        $partenaireModel->update($id, [
            'nom'         => $fields['nom'],
            'categorie'   => $fields['categorie'] !== '' ? $fields['categorie'] : null,
            'description' => $fields['description'] !== '' ? $fields['description'] : null,
            'logo'        => $logo,
            'site_web'    => Partenaire::normalizeUrl($fields['site_web']),
            'ordre'       => $fields['ordre'],
            'statut'      => $fields['statut'],
        ]);
        if (($current['logo'] ?? null) !== $logo) {
            deleteLogo($current['logo'] ?? null);   // ancien logo remplacé ou retiré
        }
        $success = 'Partenaire modifié.';
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
}

$partenaires = $partenaireModel->getAll();
$publies     = count(array_filter($partenaires, static fn (array $p): bool => $p['statut'] === 'publie'));

// ============================================================
//   HEADER ADMIN
// ============================================================
$pageTitle = 'Partenaires';
require __DIR__ . '/includes/header.php';

$h = static fn (mixed $s): string => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $h($success) ?></div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <ul><?php foreach ($errors as $msg): ?><li><?= $h($msg) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<!-- ============================================================
     FORMULAIRE D'AJOUT
     ============================================================ -->
<section class="admin-card">
    <h2>Ajouter un partenaire</h2>

    <form method="post" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="create">

        <div class="admin-form-grid">

            <div class="admin-form-row">
                <label for="nom">Nom du partenaire <span class="req">*</span></label>
                <input type="text" id="nom" name="nom" required maxlength="150"
                       value="<?= $h($old['nom'] ?? '') ?>"
                       placeholder="Ex : Ministère de la Promotion de la Femme">
            </div>

            <div class="admin-form-row">
                <label for="categorie">Type de partenariat</label>
                <input type="text" id="categorie" name="categorie" maxlength="100"
                       value="<?= $h($old['categorie'] ?? '') ?>"
                       placeholder="Ex : Partenaire institutionnel, Mécène…">
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="site_web">Site web du partenaire</label>
                <input type="text" id="site_web" name="site_web" maxlength="255" inputmode="url"
                       value="<?= $h($old['site_web'] ?? '') ?>"
                       placeholder="https://www.exemple.org">
                <small>Le bouton « Visiter le site » du partenaire mènera à cette adresse.</small>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="description">Présentation</label>
                <textarea id="description" name="description" rows="4" maxlength="1000"
                          placeholder="Qui est ce partenaire, et en quoi consiste le partenariat ?"><?= $h($old['description'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="logo">Logo</label>
                <div class="file-input-wrapper">
                    <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp,image/gif">
                    <span class="file-input-hint">JPG, PNG, WebP ou GIF · 2 Mo max · de préférence sur fond transparent ou blanc</span>
                </div>
            </div>

            <div class="admin-form-row">
                <label for="ordre">Ordre d'affichage</label>
                <input type="number" id="ordre" name="ordre" min="0" value="<?= (int) ($old['ordre'] ?? 0) ?>">
                <small>Plus le chiffre est petit, plus le partenaire apparaît en premier.</small>
            </div>

            <div class="admin-form-row">
                <label for="statut">Visibilité</label>
                <select id="statut" name="statut">
                    <option value="publie" <?= ($old['statut'] ?? 'publie') === 'publie' ? 'selected' : '' ?>>Affiché sur le site</option>
                    <option value="brouillon" <?= ($old['statut'] ?? '') === 'brouillon' ? 'selected' : '' ?>>Masqué (brouillon)</option>
                </select>
            </div>

        </div>

        <div class="admin-form-actions">
            <button type="submit" class="btn-admin">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Ajouter le partenaire
            </button>
        </div>
    </form>
</section>

<!-- ============================================================
     LISTE DES PARTENAIRES
     ============================================================ -->
<section class="admin-card">
    <h2>Partenaires enregistrés (<?= count($partenaires) ?>) <small class="admin-text-muted">— <?= (int) $publies ?> affiché(s) sur le site</small></h2>

    <?php if (empty($partenaires)): ?>
        <p class="admin-empty">Aucun partenaire enregistré pour le moment.</p>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Site web</th>
                        <th>Ordre</th>
                        <th>Visibilité</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partenaires as $p): ?>
                        <tr>
                            <td>
                                <?php if (!empty($p['logo'])): ?>
                                    <img class="admin-thumb admin-thumb--logo" src="<?= $h($p['logo']) ?>" alt="">
                                <?php else: ?>
                                    <span class="admin-text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?= $h($p['nom']) ?></strong></td>
                            <td><?= $p['categorie'] ? $h($p['categorie']) : '<span class="admin-text-muted">—</span>' ?></td>
                            <td>
                                <?php if (!empty($p['site_web'])): ?>
                                    <a href="<?= $h($p['site_web']) ?>" target="_blank" rel="noopener noreferrer"><?= $h(preg_replace('#^https?://(www\.)?#i', '', rtrim($p['site_web'], '/'))) ?> ↗</a>
                                <?php else: ?>
                                    <span class="admin-text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= (int) $p['ordre'] ?></td>
                            <td>
                                <?php if ($p['statut'] === 'publie'): ?>
                                    <span class="admin-badge admin-badge--success">Affiché</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge--muted">Masqué</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-actions">
                                <div class="action-buttons">
                                    <button type="button"
                                            class="btn-action btn-action--edit"
                                            data-edit
                                            data-id="<?= (int) $p['id'] ?>"
                                            data-nom="<?= $h($p['nom']) ?>"
                                            data-categorie="<?= $h($p['categorie'] ?? '') ?>"
                                            data-site-web="<?= $h($p['site_web'] ?? '') ?>"
                                            data-description="<?= $h($p['description'] ?? '') ?>"
                                            data-logo="<?= $h($p['logo'] ?? '') ?>"
                                            data-ordre="<?= (int) $p['ordre'] ?>"
                                            data-statut="<?= $h($p['statut']) ?>"
                                            aria-label="Modifier">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Modifier
                                    </button>

                                    <form method="post" data-confirm="Supprimer ce partenaire ?" class="form-inline">
                                        <input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
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
            <h2 id="modalTitle">Modifier le partenaire</h2>
            <button type="button" class="modal-close" id="modalClose" aria-label="Fermer">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </header>

        <form method="post" enctype="multipart/form-data" class="admin-form modal-form">
            <input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="edit_id">

            <div class="admin-form-grid">

                <div class="admin-form-row">
                    <label for="edit_nom">Nom du partenaire <span class="req">*</span></label>
                    <input type="text" id="edit_nom" name="nom" required maxlength="150">
                </div>

                <div class="admin-form-row">
                    <label for="edit_categorie">Type de partenariat</label>
                    <input type="text" id="edit_categorie" name="categorie" maxlength="100">
                </div>

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_site_web">Site web du partenaire</label>
                    <input type="text" id="edit_site_web" name="site_web" maxlength="255" inputmode="url"
                           placeholder="https://www.exemple.org">
                </div>

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_description">Présentation</label>
                    <textarea id="edit_description" name="description" rows="4" maxlength="1000"></textarea>
                </div>

                <div class="admin-form-row admin-form-row--full">
                    <label for="edit_logo">Nouveau logo (laisser vide pour conserver)</label>
                    <div class="edit-logo-current" id="editLogoCurrent" hidden>
                        <img id="editLogoPreview" class="admin-thumb admin-thumb--logo" src="" alt="Logo actuel">
                        <label class="edit-logo-remove">
                            <input type="checkbox" name="remove_logo" value="1"> Retirer le logo actuel
                        </label>
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" id="edit_logo" name="logo" accept="image/jpeg,image/png,image/webp,image/gif">
                        <span class="file-input-hint">JPG, PNG, WebP ou GIF · 2 Mo max</span>
                    </div>
                </div>

                <div class="admin-form-row">
                    <label for="edit_ordre">Ordre d'affichage</label>
                    <input type="number" id="edit_ordre" name="ordre" min="0">
                </div>

                <div class="admin-form-row">
                    <label for="edit_statut">Visibilité</label>
                    <select id="edit_statut" name="statut">
                        <option value="publie">Affiché sur le site</option>
                        <option value="brouillon">Masqué (brouillon)</option>
                    </select>
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

<script src="<?= v('/js/admin-partenaires.js') ?>" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
