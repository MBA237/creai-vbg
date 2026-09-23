<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/Article.php';
require_once __DIR__ . '/../src/Evenement.php';

$articleModel   = new Article();
$evenementModel = new Evenement();

// ============================================================
//  PARAMÈTRES
// ============================================================
$type      = $_GET['type']      ?? 'tous';     // tous | articles | evenements
$q         = trim($_GET['q']    ?? '');
$categorie = trim($_GET['cat']  ?? '');
$page      = max(1, (int) ($_GET['page'] ?? 1));
$perPage   = 9;

// Sécurise le type
if (!in_array($type, ['tous', 'articles', 'evenements'], true)) {
    $type = 'tous';
}

// ============================================================
//  CHARGEMENT DES DONNÉES
// ============================================================
$articlesResult   = ['items' => [], 'total' => 0, 'pages' => 0];
$evenementsResult = ['items' => [], 'total' => 0, 'pages' => 0];

if ($type === 'tous' || $type === 'articles') {
    $articlesResult = $articleModel->search($q, $categorie, $page, $perPage);
}

if ($type === 'tous' || $type === 'evenements') {
    $evenementsResult = $evenementModel->search($q, $page, $perPage);
}

// Fusion pour l'affichage "tous"
$items = [];

if ($type === 'articles') {
    foreach ($articlesResult['items'] as $a) {
        $a['_type'] = 'article';
        $a['_date'] = $a['publie_le'] ?: $a['created_at'];
        $items[] = $a;
    }
} elseif ($type === 'evenements') {
    foreach ($evenementsResult['items'] as $e) {
        $e['_type'] = 'evenement';
        $e['_date'] = $e['date_debut'];
        $items[] = $e;
    }
} else {
    // Tous → on mélange
    foreach ($articlesResult['items'] as $a) {
        $a['_type'] = 'article';
        $a['_date'] = $a['publie_le'] ?: $a['created_at'];
        $items[] = $a;
    }
    foreach ($evenementsResult['items'] as $e) {
        $e['_type'] = 'evenement';
        $e['_date'] = $e['date_debut'];
        $items[] = $e;
    }
    // Tri par date desc
    usort($items, fn($a, $b) => strtotime($b['_date']) <=> strtotime($a['_date']));
    // Limite à perPage sur le mélange
    $items = array_slice($items, 0, $perPage);
}

$totalItems = $articlesResult['total'] + $evenementsResult['total'];
$maxPages   = max($articlesResult['pages'], $evenementsResult['pages']);

// Catégories disponibles
$categories = $articleModel->getCategories();

// Helpers URL
function urlWith(array $params): string
{
    $current = [
        'type' => $_GET['type'] ?? 'tous',
        'q'    => $_GET['q']    ?? '',
        'cat'  => $_GET['cat']  ?? '',
        'page' => $_GET['page'] ?? 1,
    ];
    $merged = array_merge($current, $params);
    $merged = array_filter($merged, fn($v) => $v !== '' && $v !== null && $v !== 'tous' && $v !== 1);
    return '/actualites.php' . ($merged ? '?' . http_build_query($merged) : '');
}

$pageTitle = 'Actualités & Événements — CREAI-VBG';
$pageCss   = 'actualites.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="actualites-page">

    <!-- ============================================================
         HERO
         ============================================================ -->
    <section class="actualites-hero">
        <div class="actualites-hero-content">
            <span class="actualites-badge">Actualités</span>
            <h1>Nos articles &amp; événements</h1>
            <p class="actualites-lead">
                Retrouvez toutes nos publications, analyses, recherches et
                rendez-vous autour de la lutte contre les violences basées
                sur le genre.
            </p>
        </div>
    </section>

    <!-- ============================================================
         BARRE DE FILTRES
         ============================================================ -->
    <section class="actualites-filters">
        <div class="actualites-container">

            <form method="get" action="/actualites.php" class="filters-form">

                <!-- Onglets de type -->
                <div class="filters-tabs">
                    <a href="<?= htmlspecialchars(urlWith(['type' => 'tous', 'page' => 1])) ?>"
                       class="filters-tab <?= $type === 'tous' ? 'is-active' : '' ?>">
                        <span class="filters-tab-dot filters-tab-dot--teal"></span>
                        Tout
                        <span class="filters-tab-count"><?= $totalItems ?></span>
                    </a>

                    <a href="<?= htmlspecialchars(urlWith(['type' => 'articles', 'page' => 1])) ?>"
                       class="filters-tab <?= $type === 'articles' ? 'is-active' : '' ?>">
                        <span class="filters-tab-dot filters-tab-dot--teal"></span>
                        Articles
                        <span class="filters-tab-count"><?= $articlesResult['total'] ?></span>
                    </a>

                    <a href="<?= htmlspecialchars(urlWith(['type' => 'evenements', 'page' => 1])) ?>"
                       class="filters-tab <?= $type === 'evenements' ? 'is-active' : '' ?>">
                        <span class="filters-tab-dot filters-tab-dot--purple"></span>
                        Événements
                        <span class="filters-tab-count"><?= $evenementsResult['total'] ?></span>
                    </a>
                </div>

                <!-- Recherche + catégorie -->
                <div class="filters-row">

                    <div class="filters-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input type="search"
                               name="q"
                               value="<?= htmlspecialchars($q) ?>"
                               placeholder="Rechercher un article, un événement...">
                        <?php if ($q !== ''): ?>
                            <a href="<?= htmlspecialchars(urlWith(['q' => '', 'page' => 1])) ?>"
                               class="filters-clear" aria-label="Effacer la recherche">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if ($type !== 'evenements' && !empty($categories)): ?>
                        <select name="cat" class="filters-select" onchange="this.form.submit()">
                            <option value="">Toutes les catégories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>"
                                    <?= $categorie === $cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <button type="submit" class="filters-submit">
                        Rechercher
                    </button>

                </div>

                <!-- Inputs cachés pour préserver type/page -->
                <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

                <!-- Résumé des filtres actifs -->
                <?php if ($q !== '' || $categorie !== ''): ?>
                    <div class="filters-active">
                        <span class="filters-active-label">Filtres actifs :</span>

                        <?php if ($q !== ''): ?>
                            <span class="filters-chip">
                                « <?= htmlspecialchars($q) ?> »
                                <a href="<?= htmlspecialchars(urlWith(['q' => '', 'page' => 1])) ?>" aria-label="Retirer">×</a>
                            </span>
                        <?php endif; ?>

                        <?php if ($categorie !== ''): ?>
                            <span class="filters-chip">
                                <?= htmlspecialchars($categorie) ?>
                                <a href="<?= htmlspecialchars(urlWith(['cat' => '', 'page' => 1])) ?>" aria-label="Retirer">×</a>
                            </span>
                        <?php endif; ?>

                        <a href="/actualites.php" class="filters-reset">Tout effacer</a>
                    </div>
                <?php endif; ?>

            </form>

        </div>
    </section>

    <!-- ============================================================
         RÉSULTATS
         ============================================================ -->
    <section class="actualites-section">
        <div class="actualites-container">

            <?php if (empty($items)): ?>

                <div class="actualites-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <h2>Aucun résultat</h2>
                    <p>
                        <?php if ($q !== ''): ?>
                            Aucun contenu ne correspond à « <?= htmlspecialchars($q) ?> ».
                        <?php else: ?>
                            Aucun contenu publié pour le moment.
                        <?php endif; ?>
                    </p>
                    <?php if ($q !== '' || $categorie !== ''): ?>
                        <a href="/actualites.php" class="btn btn--primary">Voir tout</a>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <div class="actualites-grid">
                    <?php foreach ($items as $item): ?>

                        <?php if ($item['_type'] === 'article'):
                            $image = $item['image'] ?: '/images/articles/default.jpg';
                            $date  = $item['_date'];
                        ?>
                            <a href="/article.php?slug=<?= urlencode($item['slug']) ?>" class="actualite-card actualite-card--article">
                                <div class="actualite-card-image">
                                    <img src="<?= htmlspecialchars($image) ?>"
                                         alt="<?= htmlspecialchars($item['titre']) ?>"
                                         loading="lazy">
                                    <span class="actualite-card-tag actualite-card-tag--teal">Article</span>
                                    <?php if (!empty($item['categorie'])): ?>
                                        <span class="actualite-card-tag actualite-card-tag--gray"><?= htmlspecialchars($item['categorie']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="actualite-card-body">
                                    <time class="actualite-card-date">
                                        <?= htmlspecialchars(date('d M Y', strtotime($date))) ?>
                                    </time>
                                    <h3><?= htmlspecialchars($item['titre']) ?></h3>
                                    <p><?= htmlspecialchars($item['extrait']) ?></p>
                                    <span class="actualite-card-link">Lire l'article →</span>
                                </div>
                            </a>

                        <?php else:
                            $image = $item['image'] ?: '/images/evenements/default.jpg';
                            $date  = $item['_date'];
                        ?>
                            <button type="button"
        class="actualite-card actualite-card--event"
        data-event-modal
        data-event-id="<?= (int) $item['id'] ?>"
        data-event-titre="<?= htmlspecialchars($item['titre']) ?>"
        data-event-description="<?= htmlspecialchars($item['description']) ?>"
        data-event-contenu="<?= htmlspecialchars($item['contenu'] ?? '') ?>"
        data-event-lieu="<?= htmlspecialchars($item['lieu']) ?>"
        data-event-date-debut="<?= htmlspecialchars($item['date_debut']) ?>"
        data-event-date-fin="<?= htmlspecialchars($item['date_fin'] ?? '') ?>"
        data-event-image="<?= htmlspecialchars($item['image'] ?: '/images/evenements/default.jpg') ?>"
        data-event-slug="<?= htmlspecialchars($item['slug']) ?>">
    <div class="actualite-card-image">
        <img src="<?= htmlspecialchars($item['image'] ?: '/images/evenements/default.jpg') ?>"
             alt="<?= htmlspecialchars($item['titre']) ?>"
             loading="lazy">
        <span class="actualite-card-tag actualite-card-tag--purple">Événement</span>
        <div class="actualite-card-date-badge">
            <span class="actualite-card-day"><?= date('d', strtotime($item['date_debut'])) ?></span>
            <span class="actualite-card-month"><?= strtoupper(date('M', strtotime($item['date_debut']))) ?></span>
        </div>
    </div>
    <div class="actualite-card-body">
        <div class="actualite-card-meta">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <?= htmlspecialchars($item['lieu']) ?>
            </span>
        </div>
        <h3><?= htmlspecialchars($item['titre']) ?></h3>
        <p><?= htmlspecialchars($item['description']) ?></p>
        <span class="actualite-card-link">En savoir plus →</span>
    </div>
</button>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($maxPages > 1): ?>
                    <nav class="actualites-pagination" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                            <a href="<?= htmlspecialchars(urlWith(['page' => $page - 1])) ?>" class="pagination-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 18 9 12 15 6"/>
                                </svg>
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $maxPages; $i++): ?>
                            <a href="<?= htmlspecialchars(urlWith(['page' => $i])) ?>"
                               class="pagination-num <?= $i === $page ? 'is-active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $maxPages): ?>
                            <a href="<?= htmlspecialchars(urlWith(['page' => $page + 1])) ?>" class="pagination-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="9 18 15 12 9 6"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>

            <?php endif; ?>

        </div>
    </section>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>