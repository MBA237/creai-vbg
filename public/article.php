<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Article.php';
require_once __DIR__ . '/../src/RichText.php';

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
    header('Location: /actualites.php');
    exit;
}

$articleModel = new Article();
$article      = $articleModel->findBySlugAny($slug);

// Article introuvable ou non publié
if (!$article || $article['statut'] !== 'publie') {
    http_response_code(404);
    $pageTitle = 'Article introuvable — CREAI-VBG';
    $pageCss   = 'article.css';
    $widePage  = true;
    require __DIR__ . '/partials/header.php';
    ?>
    <div class="article-page">
        <section class="article-404">
            <div class="article-container">
                <div class="article-404-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <h1>Article introuvable</h1>
                    <p>Cet article n'existe pas ou n'est plus publié.</p>
                    <a href="/actualites.php" class="btn btn--primary">Voir toutes les actualités</a>
                </div>
            </div>
        </section>
    </div>
    <?php
    require __DIR__ . '/partials/footer.php';
    exit;
}

// Incrémente le nombre de vues (optionnel - ajoute une colonne 'vues' si tu veux)
// $articleModel->incrementViews((int) $article['id']);

$related = $articleModel->getRelated(
    (int) $article['id'],
    $article['categorie'] ?? '',
    3
);

// Temps de lecture (basé sur 200 mots/min)
$wordCount   = str_word_count(strip_tags($article['contenu']));
$readingTime = max(1, (int) ceil($wordCount / 200));

// Date d'affichage
$datePub = $article['publie_le'] ?: $article['created_at'];
$image   = $article['image'] ?: '/images/articles/default.jpg';

$pageTitle = htmlspecialchars($article['titre']) . ' — CREAI-VBG';
$pageCss   = 'article.css';
$widePage  = true;

require __DIR__ . '/partials/header.php';
?>

<div class="article-page">

    <!-- ============================================================
         HERO — Image de couverture plein écran
         ============================================================ -->
    <section class="article-hero">
        <div class="article-hero-bg">
            <img src="<?= htmlspecialchars($image) ?>"
                 alt="<?= htmlspecialchars($article['titre']) ?>">
            <div class="article-hero-overlay"></div>
        </div>

        <div class="article-hero-content">
            <div class="article-hero-inner">

                <!-- Fil d'Ariane -->
                <nav class="article-breadcrumb" aria-label="Fil d'Ariane">
                    <a href="/">Accueil</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                    <a href="/actualites.php">Actualités</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                    <span>Article</span>
                </nav>

                <!-- Catégorie -->
                <?php if (!empty($article['categorie'])): ?>
                    <span class="article-category"><?= htmlspecialchars($article['categorie']) ?></span>
                <?php endif; ?>

                <!-- Titre -->
                <h1 class="article-title"><?= htmlspecialchars($article['titre']) ?></h1>

                <!-- Métadonnées -->
                <div class="article-meta">
                    <span class="article-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <?= htmlspecialchars(date('d M Y', strtotime($datePub))) ?>
                    </span>
                    <span class="article-meta-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <?= (int) $readingTime ?> min de lecture
                    </span>
                    <?php if (!empty($article['auteur_nom'])): ?>
                        <span class="article-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <?= htmlspecialchars($article['auteur_nom']) ?>
                        </span>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         CORPS DE L'ARTICLE
         ============================================================ -->
    <section class="article-body">
        <div class="article-container">

            <!-- Extrait en chapeau -->
            <?php if (!empty($article['extrait'])): ?>
                <p class="article-excerpt">
                    <?= htmlspecialchars($article['extrait']) ?>
                </p>
            <?php endif; ?>

            <!-- Contenu -->
            <div class="article-content">
                <?= RichText::render($article['contenu']) ?>
            </div>

            <!-- Actions de partage -->
            <div class="article-actions">
                <div class="article-share">
                    <span class="article-share-label">Partager :</span>

                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('https://' . ($_SERVER['HTTP_HOST'] ?? '') . '/article.php?slug=' . urlencode($article['slug'])) ?>"
                       target="_blank" rel="noopener"
                       class="article-share-btn" aria-label="Partager sur Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                        </svg>
                    </a>

                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode('https://' . ($_SERVER['HTTP_HOST'] ?? '') . '/article.php?slug=' . urlencode($article['slug'])) ?>"
                       target="_blank" rel="noopener"
                       class="article-share-btn" aria-label="Partager sur LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode('https://' . ($_SERVER['HTTP_HOST'] ?? '') . '/article.php?slug=' . urlencode($article['slug'])) ?>&text=<?= urlencode($article['titre']) ?>"
                       target="_blank" rel="noopener"
                       class="article-share-btn" aria-label="Partager sur X">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>

                    <button type="button" class="article-share-btn article-copy" data-url="<?= htmlspecialchars('https://' . ($_SERVER['HTTP_HOST'] ?? '') . '/article.php?slug=' . urlencode($article['slug'])) ?>" aria-label="Copier le lien">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="9" y="9" width="13" height="13" rx="2"/>
                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                        </svg>
                    </button>
                </div>

                <a href="/actualites.php" class="article-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Retour aux actualités
                </a>
            </div>

        </div>
    </section>

    <!-- ============================================================
         ARTICLES SIMILAIRES
         ============================================================ -->
    <?php if (!empty($related)): ?>
    <section class="article-related">
        <div class="article-container">
            <div class="article-related-head">
                <span class="eyebrow eyebrow--teal">À lire aussi</span>
            </div>

            <div class="article-related-grid">
                <?php foreach ($related as $r):
                    $rImage = $r['image'] ?: '/images/articles/default.jpg';
                    $rDate  = $r['publie_le'] ?: $r['created_at'];
                ?>
                    <a href="/article.php?slug=<?= urlencode($r['slug']) ?>" class="related-card">
                        <div class="related-card-image">
                            <img src="<?= htmlspecialchars($rImage) ?>"
                                 alt="<?= htmlspecialchars($r['titre']) ?>"
                                 loading="lazy">
                            <?php if (!empty($r['categorie'])): ?>
                                <span class="related-card-cat"><?= htmlspecialchars($r['categorie']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="related-card-body">
                            <time class="related-card-date">
                                <?= htmlspecialchars(date('d M Y', strtotime($rDate))) ?>
                            </time>
                            <h3><?= htmlspecialchars($r['titre']) ?></h3>
                            <p><?= htmlspecialchars($r['extrait']) ?></p>
                            <span class="related-card-link">Lire l'article →</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         CTA FINAL
         ============================================================ -->
    <section class="article-cta">
        <div class="article-container">
            <h2>Agir avec nous</h2>
            <p>
                Que vous soyez chercheur(e), professionnel(le) de santé, juriste,
                éducateur(rice), ou simplement convaincu(e) que la lutte contre les
                VBG est l'affaire de tous, il y a une place pour vous.
            </p>
            <div class="article-cta-buttons">
                <a href="/rejoindre.php" class="btn btn--primary">Devenir membre</a>
                <a href="/contact.php" class="btn btn--outline">Nous contacter</a>
            </div>
        </div>
    </section>

</div>

<script src="/js/article.js" defer></script>

<?php require __DIR__ . '/partials/footer.php'; ?>