<?php
declare(strict_types=1);

/**
 * Outil en ligne de commande pour la base de connaissances de l'assistant (src/Rag.php).
 *   php rag.php build            reconstruit l'index et affiche ce qu'il contient
 *   php rag.php search "adhésion"  montre les extraits que l'assistant recevrait pour cette question
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require __DIR__ . '/environment.php';
require __DIR__ . '/src/Rag.php';

$cmd = $argv[1] ?? 'build';

if ($cmd === 'build') {
    $index = Rag::index(true);
    $pages = [];
    foreach ($index['chunks'] as $c) {
        $pages[$c['titre']] = ($pages[$c['titre']] ?? 0) + 1;
    }
    echo count($index['chunks']) . " extraits, " . count($pages) . " sources, " . count($index['df']) . " termes.\n";
    foreach ($pages as $titre => $n) {
        echo sprintf("  %3d  %s\n", $n, $titre);
    }
} elseif ($cmd === 'search') {
    $q = implode(' ', array_slice($argv, 2));
    $hits = Rag::search($q);
    if (!$hits) {
        echo "Aucun extrait pertinent (l'assistant répondrait sans contexte).\n";
    }
    foreach ($hits as $h) {
        echo "\n[{$h['score']}] {$h['titre']}  ({$h['url']})\n" . mb_substr($h['texte'], 0, 300) . "…\n";
    }
} else {
    echo "Usage : php rag.php [build|search \"question\"]\n";
}
