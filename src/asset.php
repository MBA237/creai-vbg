<?php
declare(strict_types=1);

/**
 * Chaînes de dépendances CSS. Les feuilles ne s'importent plus entre elles avec @import :
 * un @import ne peut pas être versionné, et le cache du navigateur / du CDN continuait alors
 * de servir l'ancienne version de la feuille importée. Le header charge chaque feuille,
 * dans l'ordre, avec sa propre version (voir css_chain() et partials/header.php).
 */
const CSS_DEPENDENCIES = [
    'apprentissages.css'   => ['contenu.css'],
    'connaissances.css'    => ['contenu.css'],
    'mentions-legales.css' => ['contenu.css'],
    'notre-equipe.css'     => ['apropos.css'],
    'partenaires.css'      => ['apropos.css'],
    'rejoindre.css'        => ['contenu.css'],
    'signalement.css'      => ['besoin-aide.css'],
    'soutenir.css'         => ['rejoindre.css'],
];

if (!function_exists('web_root')) {
    /**
     * Dossier web servi (celui qui contient css/, js/, images/).
     * En local c'est « public », chez Hostinger c'est « public_html » : on lit donc ce que
     * le serveur indique (DOCUMENT_ROOT) plutôt que de supposer un nom de dossier.
     */
    function web_root(): string
    {
        static $root = null;
        if ($root === null) {
            $candidate = rtrim((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''), '/\\');
            $root = ($candidate !== '' && is_dir($candidate)) ? $candidate : dirname(__DIR__) . '/public';
        }
        return $root;
    }
}

if (!function_exists('v')) {
    /**
     * Ajoute ?v=<date de modification> à un fichier de /public (ex. '/css/base.css').
     * L'URL change dès que le fichier change : plus de cache périmé (navigateur, CDN, LiteSpeed).
     */
    function v(string $path): string
    {
        $file = web_root() . $path;
        return is_file($file) ? $path . '?v=' . filemtime($file) : $path;
    }
}

if (!function_exists('css_chain')) {
    /** Feuille demandée précédée de ses dépendances (récursif, sans doublon), dans l'ordre de chargement. */
    function css_chain(string $css): array
    {
        $chain = [];
        $visit = static function (string $file) use (&$visit, &$chain): void {
            foreach (CSS_DEPENDENCIES[$file] ?? [] as $dependency) {
                $visit($dependency);
            }
            if (!in_array($file, $chain, true)) {
                $chain[] = $file;
            }
        };
        $visit($css);
        return $chain;
    }
}

if (!function_exists('image_or')) {
    /**
     * Image locale si le fichier existe sur le serveur, sinon $fallback.
     * Évite les images cassées (image absente du dossier images/, ou casse différente sous Linux).
     * Les URL absolues http(s) sont conservées telles quelles.
     */
    function image_or(?string $image, string $fallback): string
    {
        $image = (string) $image;
        if ($image === '') {
            return $fallback;
        }
        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }
        $path = parse_url($image, PHP_URL_PATH);
        return is_string($path) && is_file(web_root() . $path) ? $image : $fallback;
    }
}
