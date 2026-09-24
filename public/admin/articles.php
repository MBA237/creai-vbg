<?php
declare(strict_types=1);

require __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/Article.php';
require_once __DIR__ . '/../../src/RichText.php';
require_once __DIR__ . '/../../src/ImageUpload.php';

$articleModel = new Article();

$errors = [];
$old    = [];   // saisie conservée quand l'enregistrement échoue
$editId = 0;    // article en cours de modification (0 = nouvel article)

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// ------------------------------------------------------------
//   Outils
// ------------------------------------------------------------

/** Slug lisible : « Éducation à l'égalité » => « education-a-l-egalite ». */
function makeSlug(string $text): string
{
    $text = mb_strtolower(trim($text));
    $text = strtr($text, [
        'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ã' => 'a', 'ç' => 'c',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'î' => 'i', 'ï' => 'i', 'í' => 'i',
        'ô' => 'o', 'ö' => 'o', 'ó' => 'o', 'õ' => 'o', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ú' => 'u', 'ÿ' => 'y', 'œ' => 'oe', 'æ' => 'ae', 'ñ' => 'n',
    ]);
    $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', $text), '-');
    return mb_substr($slug !== '' ? $slug : 'article', 0, 200);
}

/** Slug qui n'existe pas encore (ajoute -2, -3... si besoin). */
function uniqueSlug(Article $model, string $base, int $excludeId = 0): string
{
    $slug = $base;
    for ($i = 2; $model->slugExists($slug, $excludeId); $i++) {
        $slug = $base . '-' . $i;
    }
    return $slug;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash_articles'] = ['message' => $message, 'type' => $type];
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
        $id = (int) ($_POST['id'] ?? 0);
        $article = $id > 0 ? $articleModel->find($id) : null;

        if ($article) {
            if (!empty($article['image']) && file_exists(__DIR__ . '/..' . $article['image'])) {
                @unlink(__DIR__ . '/..' . $article['image']);
            }
            $articleModel->delete($id);
            flash('Article « ' . $article['titre'] . ' » supprimé.');
        }
        redirect('/admin/articles.php');

    // ---------------- Publier / repasser en brouillon (depuis le tableau) ----------------
    } elseif ($action === 'set_statut') {
        $id     = (int) ($_POST['id'] ?? 0);
        $statut = (string) ($_POST['statut'] ?? '');
        $article = $id > 0 ? $articleModel->find($id) : null;

        if ($article && in_array($statut, Article::STATUTS, true)) {
            $articleModel->setStatut($id, $statut);
            flash($statut === 'publie'
                ? 'Article « ' . $article['titre'] . ' » publié.'
                : 'Article « ' . $article['titre'] . ' » repassé en brouillon : il n\'est plus visible sur le site.');
        }
        redirect('/admin/articles.php');

    // ---------------- Création / modification ----------------
    } elseif ($action === 'create' || $action === 'update') {

        $id       = $action === 'update' ? (int) ($_POST['id'] ?? 0) : 0;
        $existing = $id > 0 ? $articleModel->find($id) : null;

        // Le bouton cliqué décide du statut ; sans information, on reste en brouillon
        $statut = (string) ($_POST['statut'] ?? 'brouillon');
        if (!in_array($statut, Article::STATUTS, true)) {
            $statut = 'brouillon';
        }

        $titre       = trim((string) ($_POST['titre'] ?? ''));
        $extrait     = trim((string) ($_POST['extrait'] ?? ''));
        $auteur      = trim((string) ($_POST['auteur'] ?? ''));
        $categorie   = trim((string) ($_POST['categorie'] ?? ''));
        $contenuBrut = (string) ($_POST['contenu'] ?? '');
        $publieRaw   = trim((string) ($_POST['publie_le'] ?? ''));

        // Le HTML de l'éditeur est TOUJOURS nettoyé côté serveur (liste blanche)
        $contenu = strlen($contenuBrut) > RichText::MAX_BYTES ? '' : RichText::sanitize($contenuBrut);

        if ($action === 'update' && !$existing) $errors[] = 'Article introuvable.';
        if ($titre === '')   $errors[] = 'Le titre est obligatoire.';
        if ($extrait === '') $errors[] = 'L\'extrait est obligatoire.';
        if ($auteur === '')  $errors[] = 'Le nom de l\'auteur est obligatoire.';

        if (strlen($contenuBrut) > RichText::MAX_BYTES) {
            $errors[] = 'Le contenu est trop volumineux : réduisez le texte ou le nombre d\'éléments.';
        } elseif (RichText::isEmpty($contenu)) {
            $errors[] = 'Le contenu est obligatoire.';
        }

        // Catégorie : uniquement la liste (ou celle déjà enregistrée sur l'article modifié)
        $allowedCategories = Article::CATEGORIES;
        if (!empty($existing['categorie'])) {
            $allowedCategories[] = $existing['categorie'];
        }
        if ($categorie !== '' && !in_array($categorie, $allowedCategories, true)) {
            $errors[] = 'Catégorie invalide : choisissez une catégorie dans la liste.';
        }

        // Date de publication : celle saisie, sinon celle déjà enregistrée, sinon maintenant (à la publication)
        $publieLe = null;
        if ($publieRaw !== '') {
            $dt = DateTime::createFromFormat('Y-m-d\TH:i', $publieRaw);
            if ($dt && $dt->format('Y-m-d\TH:i') === $publieRaw) {
                $publieLe = $dt->format('Y-m-d H:i:00');
            } else {
                $errors[] = 'Date de publication invalide.';
            }
        } else {
            $publieLe = $existing['publie_le'] ?? null;
        }
        if ($statut === 'publie' && $publieLe === null) {
            $publieLe = date('Y-m-d H:i:s');
        }

        // Saisie conservée si quelque chose échoue
        $old = [
            'titre'     => $titre,
            'categorie' => $categorie,
            'auteur'    => $auteur,
            'extrait'   => $extrait,
            'contenu'   => $contenu,
            'publie_le' => $publieRaw,
        ];
        $editId = $id;

        // Image de couverture (type vérifié sur le contenu réel du fichier)
        $image = $existing['image'] ?? null;
        $newImage = null;
        if (empty($errors) && !empty($_FILES['image']['name'])) {
            $up = ImageUpload::store($_FILES['image'], __DIR__ . '/../images/articles', '/images/articles', 'article');
            if (isset($up['error'])) {
                $errors[] = $up['error'];
            } else {
                $newImage = $up['url'];
            }
        }

        if (empty($errors)) {
            if ($newImage !== null) {
                if ($image && file_exists(__DIR__ . '/..' . $image)) {
                    @unlink(__DIR__ . '/..' . $image);
                }
                $image = $newImage;
            }

            $data = [
                'titre'     => $titre,
                'extrait'   => $extrait,
                'contenu'   => $contenu,
                'image'     => $image,
                'auteur'    => $auteur,
                'categorie' => $categorie,
                'statut'    => $statut,
                'publie_le' => $publieLe,
            ];

            if ($action === 'create') {
                $data['slug'] = uniqueSlug($articleModel, makeSlug($titre));
                $articleModel->create($data);
                flash($statut === 'publie'
                    ? 'Article publié.'
                    : 'Article enregistré en brouillon. Publiez-le depuis le tableau ci-dessous quand il est prêt.');
            } else {
                // Le lien de l'article ne change jamais après sa création (liens déjà partagés)
                $data['slug'] = $existing['slug'];
                $articleModel->update($id, $data);
                flash($statut === 'publie'
                    ? ($existing['statut'] === 'publie' ? 'Article mis à jour.' : 'Article publié.')
                    : 'Article enregistré en brouillon.');
            }

            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            redirect('/admin/articles.php');
        }
    }
}

// ============================================================
//   AFFICHAGE
// ============================================================

// Message mémorisé avant une redirection
$flash = $_SESSION['flash_articles'] ?? null;
unset($_SESSION['flash_articles']);

// Mode modification : ?edit=ID
if ($editId === 0) {
    $editId = (int) ($_GET['edit'] ?? 0);
}

$editing = null;
if ($editId > 0) {
    $editing = $articleModel->find($editId);
    if (!$editing) {
        flash('Cet article n\'existe plus.', 'error');
        redirect('/admin/articles.php');
    }
}

// Valeurs du formulaire : nouvel article, article existant, ou saisie conservée après une erreur
$form = [
    'titre'     => '',
    'categorie' => '',
    'auteur'    => (string) ($currentAdmin['nom'] ?? ''),
    'extrait'   => '',
    'contenu'   => '',
    'publie_le' => '',
];
if ($editing) {
    $form = [
        'titre'     => $editing['titre'],
        'categorie' => (string) ($editing['categorie'] ?? ''),
        'auteur'    => (string) ($editing['auteur'] ?? ''),
        'extrait'   => $editing['extrait'],
        'contenu'   => RichText::forEditor($editing['contenu']), // anciens textes bruts => paragraphes
        'publie_le' => !empty($editing['publie_le']) ? str_replace(' ', 'T', substr($editing['publie_le'], 0, 16)) : '',
    ];
}
if ($old) {
    $form = array_merge($form, $old);
}

// Catégories du menu déroulant (+ celle déjà enregistrée si elle n'est plus dans la liste)
$categoryOptions = Article::CATEGORIES;
$legacyCategory  = '';
if ($form['categorie'] !== '' && !in_array($form['categorie'], $categoryOptions, true)) {
    $legacyCategory = $form['categorie'];
}

$isPublished = $editing && $editing['statut'] === 'publie';
$articles    = $articleModel->getAll();

$pageTitle = 'Actualités';
$extraCss  = ['/js/vendor/quill/quill.snow.css', '/css/admin-rich-editor.css'];
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
<section class="admin-card <?= $editing ? 'admin-card--editing' : '' ?>" id="article-form">

    <div class="admin-card-head">
        <h2><?= $editing ? 'Modifier l\'article' : 'Nouvel article' ?></h2>

        <?php if ($editing): ?>
            <div class="admin-card-head-meta">
                <span class="admin-badge <?= $isPublished ? 'admin-badge--success' : 'admin-badge--warning' ?>">
                    <?= $isPublished ? 'Publié' : 'Brouillon' ?>
                </span>
                <?php if ($isPublished): ?>
                    <a href="/article.php?slug=<?= urlencode($editing['slug']) ?>" target="_blank" rel="noopener" class="admin-link">
                        Voir sur le site ↗
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <form method="post" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
        <input type="hidden" name="action" value="<?= $editing ? 'update' : 'create' ?>">
        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= (int) $editing['id'] ?>">
        <?php endif; ?>

        <div class="admin-form-grid">

            <div class="admin-form-row admin-form-row--full">
                <label for="titre">Titre <span class="req">*</span></label>
                <input type="text" id="titre" name="titre" required maxlength="200"
                       value="<?= htmlspecialchars($form['titre']) ?>"
                       placeholder="Ex : Nouvelle recherche sur les VBG au Cameroun">
            </div>

            <div class="admin-form-row">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie">
                    <option value="">— Aucune —</option>
                    <?php foreach ($categoryOptions as $c): ?>
                        <option value="<?= htmlspecialchars($c) ?>" <?= $form['categorie'] === $c ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c) ?>
                        </option>
                    <?php endforeach; ?>
                    <?php if ($legacyCategory !== ''): ?>
                        <option value="<?= htmlspecialchars($legacyCategory) ?>" selected>
                            <?= htmlspecialchars($legacyCategory) ?> (ancienne catégorie)
                        </option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="admin-form-row">
                <label for="auteur">Auteur <span class="req">*</span></label>
                <input type="text" id="auteur" name="auteur" required maxlength="150"
                       value="<?= htmlspecialchars($form['auteur']) ?>"
                       placeholder="Nom de l'auteur de l'article">
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="extrait">Extrait <span class="req">*</span></label>
                <textarea id="extrait" name="extrait" rows="3" required maxlength="500"
                          placeholder="Résumé court (2-3 phrases) qui apparaîtra dans la liste."><?= htmlspecialchars($form['extrait']) ?></textarea>
                <small>500 caractères max.</small>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="contenu">Contenu <span class="req">*</span></label>
                <textarea id="contenu" name="contenu" rows="12"
                          data-rich-editor
                          data-upload-url="/admin/upload-image.php"
                          data-csrf="<?= htmlspecialchars($_SESSION['csrf']) ?>"><?= htmlspecialchars($form['contenu']) ?></textarea>
                <small>Mettez en forme votre texte : police, taille, couleurs, citations, listes, liens et images. Cliquez sur une image pour la redimensionner ou l'aligner.</small>
            </div>

            <div class="admin-form-row admin-form-row--full">
                <label for="image"><?= $editing && $editing['image'] ? 'Image de couverture' : 'Image de couverture' ?></label>
                <?php if ($editing && !empty($editing['image'])): ?>
                    <div class="form-current-image">
                        <img class="admin-thumb" src="<?= htmlspecialchars($editing['image']) ?>" alt="">
                        <span>Image actuelle. Choisissez un fichier pour la remplacer, sinon elle est conservée.</span>
                    </div>
                <?php endif; ?>
                <div class="file-input-wrapper">
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                    <span class="file-input-hint">JPG, PNG ou WebP · 3 Mo max · Format 16:10 recommandé</span>
                </div>
            </div>

            <div class="admin-form-row">
                <label for="publie_le">Date de publication</label>
                <input type="datetime-local" id="publie_le" name="publie_le"
                       value="<?= htmlspecialchars($form['publie_le']) ?>">
                <small>Facultatif. Vide = date du moment où vous publiez.</small>
            </div>

        </div>

        <!-- Le bouton cliqué décide du statut. Le PREMIER bouton est celui de la touche Entrée : on reste en brouillon. -->
        <div class="admin-form-actions admin-form-actions--split">
            <p class="admin-form-hint">
                <?php if ($editing && $isPublished): ?>
                    Cet article est <strong>publié</strong> : « Mettre à jour » applique vos changements sur le site.
                <?php elseif ($editing): ?>
                    Cet article est un <strong>brouillon</strong> : il n'est pas visible sur le site.
                <?php else: ?>
                    Un nouvel article est d'abord enregistré en <strong>brouillon</strong>, invisible sur le site tant que vous ne le publiez pas.
                <?php endif; ?>
            </p>

            <div class="admin-form-buttons">
                <?php if ($editing): ?>
                    <a href="/admin/articles.php" class="btn-admin btn-admin--ghost">Annuler</a>
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
     LISTE DES ARTICLES
     ============================================================ -->
<section class="admin-card">
    <h2>Articles enregistrés (<?= count($articles) ?>)</h2>

    <?php if (empty($articles)): ?>
        <p class="admin-empty">Aucun article pour le moment.</p>
    <?php else: ?>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Publié le</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $a):
                        $published = $a['statut'] === 'publie';
                    ?>
                        <tr class="<?= $editing && (int) $editing['id'] === (int) $a['id'] ? 'is-editing' : '' ?>">
                            <td>
                                <img class="admin-thumb"
                                     src="<?= htmlspecialchars($a['image'] ?: '/images/articles/default.jpg') ?>"
                                     alt="">
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($a['titre']) ?></strong>
                                <br>
                                <small class="admin-text-muted">
                                    <?= htmlspecialchars(mb_substr($a['extrait'], 0, 80)) ?>…
                                </small>
                            </td>
                            <td>
                                <?php if (!empty($a['categorie'])): ?>
                                    <span class="admin-badge"><?= htmlspecialchars($a['categorie']) ?></span>
                                <?php else: ?>
                                    <span class="admin-text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($published): ?>
                                    <span class="admin-badge admin-badge--success">Publié</span>
                                <?php else: ?>
                                    <span class="admin-badge admin-badge--warning">Brouillon</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($a['publie_le']) && $published): ?>
                                    <?= htmlspecialchars(date('d/m/Y H:i', strtotime($a['publie_le']))) ?>
                                <?php else: ?>
                                    <span class="admin-text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="td-actions">
                                <div class="action-buttons">

                                    <!-- Publier / Dépublier -->
                                    <form method="post" class="form-inline"
                                          <?= $published ? 'data-confirm="Repasser cet article en brouillon ? Il ne sera plus visible sur le site."' : '' ?>>
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="set_statut">
                                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
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
                                    <a href="/admin/articles.php?edit=<?= (int) $a['id'] ?>#article-form"
                                       class="btn-action btn-action--edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        Modifier
                                    </a>

                                    <form method="post" data-confirm="Supprimer cet article ?" class="form-inline">
                                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
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

<script src="<?= v('/js/vendor/quill/quill.js') ?>" defer></script>
<script src="<?= v('/js/admin-rich-editor.js') ?>" defer></script>

<?php require __DIR__ . '/includes/footer.php'; ?>
