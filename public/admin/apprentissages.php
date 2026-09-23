<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Apprentissage.php';

$model = new Apprentissage();

$errors = [];
$old    = [];   // saisie conservée quand l'enregistrement échoue
$editId = 0;    // fiche en cours de modification (0 = nouvelle fiche)

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash_apprentissages'] = ['message' => $message, 'type' => $type];
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

// ============================================================
//   ACTIONS (POST)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = (string) ($_POST['action'] ?? '');

    if (!hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) {
        $errors[] = 'Jeton de sécurité invalide : rechargez la page puis réessayez.';

    // ---------------- Suppression ----------------
    } elseif ($action === 'delete') {
        $id   = (int) ($_POST['id'] ?? 0);
        $item = $id > 0 ? $model->find($id) : null;
        if ($item) {
            $model->delete($id);
            flash('Lien « ' . $item['titre'] . ' » supprimé.');
        }
        redirect('/admin/apprentissages.php');

    // ---------------- Publier / repasser en brouillon (depuis le tableau) ----------------
    } elseif ($action === 'set_statut') {
        $id     = (int) ($_POST['id'] ?? 0);
        $statut = (string) ($_POST['statut'] ?? '');
        $item   = $id > 0 ? $model->find($id) : null;

        if ($item && in_array($statut, Apprentissage::STATUTS, true)) {
            $model->setStatut($id, $statut);
            flash($statut === 'publie'
                ? 'Lien « ' . $item['titre'] . ' » publié sur le Centre d\'apprentissage.'
                : 'Lien « ' . $item['titre'] . ' » repassé en brouillon : il n\'est plus visible sur le site.');
        }
        redirect('/admin/apprentissages.php');

    // ---------------- Création / modification ----------------
    } elseif ($action === 'create' || $action === 'update') {

        $id       = $action === 'update' ? (int) ($_POST['id'] ?? 0) : 0;
        $existing = $id > 0 ? $model->find($id) : null;

        // Le bouton cliqué décide du statut ; sans information, on reste en brouillon
        $statut = (string) ($_POST['statut'] ?? 'brouillon');
        if (!in_array($statut, Apprentissage::STATUTS, true)) {
            $statut = 'brouillon';
        }

        $titre       = trim((string) ($_POST['titre'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $type        = (string) ($_POST['type'] ?? '');
        $languesPost = $_POST['langues'] ?? [];
        $langues     = Apprentissage::parseLangues(is_array($languesPost) ? array_filter($languesPost, 'is_string') : []);
        $source      = trim((string) ($_POST['source'] ?? ''));
        $urlRaw      = trim((string) ($_POST['url'] ?? ''));
        $dateRaw     = trim((string) ($_POST['date_formation'] ?? ''));

        if ($action === 'update' && !$existing) $errors[] = 'Lien introuvable.';
        if ($titre === '')       $errors[] = 'Le titre est obligatoire.';
        if (mb_strlen($titre) > 200) $errors[] = 'Le titre est trop long (200 caractères max).';
        if ($description === '') $errors[] = 'La description est obligatoire.';
        if (mb_strlen($description) > 600) $errors[] = 'La description est trop longue (600 caractères max).';
        if (!isset(Apprentissage::TYPES[$type]))   $errors[] = 'Choisissez un type dans la liste.';
        if (empty($langues)) $errors[] = 'Choisissez au moins une langue (français, anglais, ou les deux).';
        if (mb_strlen($source) > 150) $errors[] = 'La source est trop longue (150 caractères max).';

        $url = Apprentissage::cleanUrl($urlRaw);
        if ($urlRaw === '') {
            $errors[] = 'Le lien de la formation est obligatoire.';
        } elseif ($url === null) {
            $errors[] = 'Le lien n\'est pas valide : il doit commencer par http:// ou https:// (ex. https://exemple.org/formation).';
        }

        $dateFormation = null;
        if ($dateRaw !== '') {
            $dt = DateTime::createFromFormat('Y-m-d', $dateRaw);
            if ($dt && $dt->format('Y-m-d') === $dateRaw) {
                $dateFormation = $dateRaw;
            } else {
                $errors[] = 'Date invalide.';
            }
        }

        // Saisie conservée si quelque chose échoue
        $old = [
            'titre'          => $titre,
            'description'    => $description,
            'type'           => $type,
            'langues'        => $langues,
            'source'         => $source,
            'url'            => $urlRaw,
            'date_formation' => $dateRaw,
        ];
        $editId = $id;

        if (empty($errors)) {
            // Date de publication : conservée si déjà publié un jour, sinon maintenant à la publication
            $publieLe = $existing['publie_le'] ?? null;
            if ($statut === 'publie' && $publieLe === null) {
                $publieLe = date('Y-m-d H:i:s');
            }

            $data = [
                'titre'          => $titre,
                'description'    => $description,
                'type'           => $type,
                'langue'         => implode(',', $langues),
                'source'         => $source !== '' ? $source : null,
                'url'            => $url,
                'date_formation' => $dateFormation,
                'statut'         => $statut,
                'publie_le'      => $publieLe,
            ];

            if ($action === 'create') {
                $model->create($data);
                flash($statut === 'publie'
                    ? 'Lien publié sur le Centre d\'apprentissage.'
                    : 'Lien enregistré en brouillon. Publiez-le depuis le tableau ci-dessous quand il est prêt.');
            } else {
                $model->update($id, $data);
                flash($statut === 'publie'
                    ? ($existing['statut'] === 'publie' ? 'Lien mis à jour.' : 'Lien publié sur le Centre d\'apprentissage.')
                    : 'Lien enregistré en brouillon.');
            }

            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            redirect('/admin/apprentissages.php');
        }
    }
}

// ============================================================
//   AFFICHAGE
// ============================================================

$flash = $_SESSION['flash_apprentissages'] ?? null;
unset($_SESSION['flash_apprentissages']);

// Mode modification : ?edit=ID
if ($editId === 0) {
    $editId = (int) ($_GET['edit'] ?? 0);
}

$editing = null;
if ($editId > 0) {
    $editing = $model->find($editId);
    if (!$editing) {
        flash('Ce lien n\'existe plus.', 'error');
        redirect('/admin/apprentissages.php');
    }
}

// Valeurs du formulaire : nouvelle fiche, fiche existante, ou saisie conservée après une erreur
$form = [
    'titre' => '', 'description' => '', 'type' => '', 'langues' => ['fr'],
    'source' => '', 'url' => '', 'date_formation' => '',
];
if ($editing) {
    $form = [
        'titre'          => $editing['titre'],
        'description'    => $editing['description'],
        'type'           => $editing['type'],
        'langues'        => Apprentissage::parseLangues($editing['langue']),
        'source'         => (string) ($editing['source'] ?? ''),
        'url'            => $editing['url'],
        'date_formation' => (string) ($editing['date_formation'] ?? ''),
    ];
}
if ($old) {
    $form = array_merge($form, $old);
}

$isPublished = $editing && $editing['statut'] === 'publie';
$items       = $model->getAll();

$pageTitle = 'Centre d\'apprentissage';
require __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-error">
        <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<!-- ============================================================
     FORMULAIRE : création ET modification (au même endroit)
     ============================================================ -->
<section class="admin-card <?= $editing ? 'admin-card--editing' : '' ?>" id="apprentissage-form">

    <div class="admin-card-head">
        <h2><?= $editing ? 'Modifier le lien d\'apprentissage' : 'Nouveau lien d\'apprentissage' ?></h2>

        <div class="admin-card-head-meta">
            <?php if ($editing): ?>
                <span class="admin-badge <?= $isPublished ? 'admin-badge--success' : 'admin-badge--warning' ?>">
                    <?= $isPublished ? 'Publié' : 'Brouillon' ?>
                </span>
            <?php endif; ?>
            <?php if (!$editing || $isPublished): ?>
                <a href="/apprentissage.php#formations" target="_blank" rel="noopener" class="admin-link">
                    Voir la page publique ↗
                </a>
            <?php endif; ?>
        </div>
    </div>

    <form method="post" class="admin-form">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>">
        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= (int) $editing['id'] ?>">
        <?php endif; ?>

        <div class="admin-form-grid">

            <div class="admin-form-row admin-form-row--full">
                <label for="titre">Titre de la formation <span class="req">*</span></label>
                <input type="text" id="titre" name="titre" required maxlength="200"
                       value="<?= htmlspecialchars($form['titre']) ?>"
                       placeholder="Ex : Introduction à la prévention des VBG en milieu communautaire">
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="url">Lien de la formation <span class="req">*</span></label>
                <input type="url" id="url" name="url" required maxlength="500"
                       value="<?= htmlspecialchars($form['url']) ?>"
                       placeholder="https://exemple.org/formation">
                <small>L'adresse vers laquelle les visiteurs seront envoyés (cours en ligne, inscription au webinaire, document...).</small>
            </div>

            <div class="admin-form-row">
                <label for="type">Type <span class="req">*</span></label>
                <select id="type" name="type" required>
                    <option value="">— Choisir un type —</option>
                    <?php foreach (Apprentissage::TYPES as $value => $label): ?>
                        <option value="<?= htmlspecialchars($value) ?>" <?= $form['type'] === $value ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="admin-form-row">
                <span class="admin-label" id="langues-label">Langue(s) <span class="req">*</span></span>
                <div class="admin-checks" role="group" aria-labelledby="langues-label">
                    <?php foreach (Apprentissage::LANGUES as $value => $label): ?>
                        <label class="admin-check">
                            <input type="checkbox" name="langues[]" value="<?= htmlspecialchars($value) ?>"
                                   <?= in_array($value, $form['langues'], true) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <small>Cochez les deux si la formation existe en français et en anglais.</small>
            </div>

            <div class="admin-form-row">
                <label for="source">Organisme / source</label>
                <input type="text" id="source" name="source" maxlength="150"
                       value="<?= htmlspecialchars($form['source']) ?>"
                       placeholder="Ex : ONU Femmes, partenaire, CREAI-VBG...">
            </div>

            <div class="admin-form-row">
                <label for="date_formation">Date</label>
                <input type="date" id="date_formation" name="date_formation"
                       value="<?= htmlspecialchars($form['date_formation']) ?>">
                <small>Facultatif : date du webinaire ou de la formation.</small>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="description">Description <span class="req">*</span></label>
                <textarea id="description" name="description" rows="4" required maxlength="600"
                          placeholder="À qui s'adresse cette formation et que va-t-on y apprendre ? (600 caractères max)"><?= htmlspecialchars($form['description']) ?></textarea>
            </div>

        </div>

        <!-- Le bouton cliqué décide du statut. Le PREMIER bouton est celui de la touche Entrée : on reste en brouillon. -->
        <div class="admin-form-actions admin-form-actions--split">
            <p class="admin-form-hint">
                <?php if ($editing && $isPublished): ?>
                    Ce lien est <strong>publié</strong> : « Mettre à jour » applique vos changements sur le site.
                <?php elseif ($editing): ?>
                    Ce lien est un <strong>brouillon</strong> : il n'est pas visible sur le site.
                <?php else: ?>
                    Un nouveau lien est d'abord enregistré en <strong>brouillon</strong>, invisible sur le site tant que vous ne le publiez pas.
                <?php endif; ?>
            </p>

            <div class="admin-form-buttons">
                <?php if ($editing): ?>
                    <a href="/admin/apprentissages.php" class="btn-admin btn-admin--ghost">Annuler</a>
                <?php endif; ?>

                <button type="submit" name="statut" value="brouillon" class="btn-admin btn-admin--ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <?= $isPublished ? 'Repasser en brouillon' : 'Enregistrer en brouillon' ?>
                </button>

                <button type="submit" name="statut" value="publie" class="btn-admin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    <?= $isPublished ? 'Mettre à jour' : 'Publier' ?>
                </button>
            </div>
        </div>
    </form>
</section>

<!-- ============================================================
     LISTE DES LIENS
     ============================================================ -->
<section class="admin-card">
    <h2>Liens enregistrés (<?= count($items) ?>)</h2>

    <?php if (empty($items)): ?>
        <p class="admin-empty">Aucun lien d'apprentissage pour le moment.</p>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Langues</th>
                        <th>Statut</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it):
                        $published = $it['statut'] === 'publie';
                    ?>
                        <tr class="<?= $editing && (int) $editing['id'] === (int) $it['id'] ? 'is-editing' : '' ?>">
                            <td>
                                <strong><?= htmlspecialchars($it['titre']) ?></strong>
                                <br>
                                <a href="<?= htmlspecialchars($it['url']) ?>" target="_blank" rel="noopener noreferrer" class="admin-link admin-link--small">
                                    <?= htmlspecialchars(mb_strimwidth(preg_replace('#^https?://#', '', $it['url']), 0, 55, '…')) ?> ↗
                                </a>
                            </td>
                            <td><span class="admin-badge"><?= htmlspecialchars(Apprentissage::TYPES[$it['type']] ?? $it['type']) ?></span></td>
                            <td><?= htmlspecialchars(implode(' · ', array_map('strtoupper', Apprentissage::parseLangues($it['langue'])))) ?></td>
                            <td>
                                <?php if ($published): ?>
                                    <span class="admin-badge admin-badge--success">Publié</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge--warning">Brouillon</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-actions">
                                <div class="action-buttons">

                                    <!-- Publier / Dépublier -->
                                    <form method="post" class="form-inline"
                                          <?= $published ? 'data-confirm="Repasser ce lien en brouillon ? Il ne sera plus visible sur le site."' : '' ?>>
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="set_statut">
                                        <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
                                        <input type="hidden" name="statut" value="<?= $published ? 'brouillon' : 'publie' ?>">
                                        <?php if ($published): ?>
                                            <button type="submit" class="btn-action btn-action--unpublish">Dépublier</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn-action btn-action--publish">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="22" y1="2" x2="11" y2="13"/>
                                                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                                </svg>
                                                Publier
                                            </button>
                                        <?php endif; ?>
                                    </form>

                                    <!-- Modifier : ouvre le formulaire du haut, pré-rempli -->
                                    <a href="/admin/apprentissages.php?edit=<?= (int) $it['id'] ?>#apprentissage-form"
                                       class="btn-action btn-action--edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Modifier
                                    </a>

                                    <form method="post" data-confirm="Supprimer ce lien d'apprentissage ?" class="form-inline">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $it['id'] ?>">
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

<?php require __DIR__ . '/includes/footer.php'; ?>
