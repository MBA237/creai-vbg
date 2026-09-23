<?php
declare(strict_types=1);

/**
 * Contenu riche (articles) : nettoyage, contrôle et affichage.
 *
 * Le HTML produit par l'éditeur de l'admin est TOUJOURS nettoyé ici :
 * seules les balises, attributs et styles de la liste blanche sont conservés.
 * Tout le reste (script, iframe, onclick, javascript:, styles inconnus...)
 * est supprimé, de sorte qu'un contenu malveillant ne puisse jamais
 * s'exécuter sur le site public.
 */
final class RichText
{
    /** Taille maximale du contenu avant nettoyage (octets). */
    public const MAX_BYTES = 1_000_000;

    /** Balises conservées. */
    private const ALLOWED = [
        'p', 'br', 'strong', 'em', 'u', 's', 'sub', 'sup',
        'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
        'a', 'img', 'span',
    ];

    /** Balises renommées vers une balise équivalente. */
    private const RENAMED = [
        'b' => 'strong', 'i' => 'em', 'strike' => 's', 'del' => 's',
        'h1' => 'h2', 'h5' => 'h4', 'h6' => 'h4',
    ];

    /** Balises supprimées avec tout leur contenu. */
    private const DROPPED = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button',
        'textarea', 'select', 'option', 'svg', 'math', 'link', 'meta', 'base',
        'noscript', 'template', 'video', 'audio', 'canvas', 'head', 'title',
    ];

    /** Balises qui acceptent un attribut style filtré. */
    private const STYLABLE = ['p', 'span', 'h2', 'h3', 'h4', 'li', 'blockquote', 'ul', 'ol'];

    /** Polices autorisées (valeur normalisée en minuscules => valeur écrite). */
    private const FONTS = [
        'inter'           => 'Inter',
        'arial'           => 'Arial',
        'verdana'         => 'Verdana',
        'georgia'         => 'Georgia',
        'serif'           => 'serif',
        'sans-serif'      => 'sans-serif',
        'monospace'       => 'monospace',
        'times new roman' => 'serif',
        'courier new'     => 'monospace',
    ];

    /**
     * Nettoie du HTML : retourne un HTML sûr.
     */
    public static function sanitize(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $previous = libxml_use_internal_errors(true);
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->loadHTML(
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"><div id="rt-root">' . $html . '</div>',
            LIBXML_NONET
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $doc->getElementById('rt-root');
        if (!$root) {
            return '';
        }

        return trim(self::children($root));
    }

    /** Vrai si le contenu n'a ni texte visible ni image. */
    public static function isEmpty(string $html): bool
    {
        if (stripos($html, '<img') !== false) {
            return false;
        }
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/[\s\x{00A0}]+/u', '', $text) ?? '';
        return $text === '';
    }

    /**
     * HTML à afficher sur le site public.
     * Les anciens articles (texte brut) sont mis en paragraphes.
     */
    public static function render(string $contenu): string
    {
        if (!self::looksLikeHtml($contenu)) {
            return self::plainTextToHtml($contenu);
        }
        return self::sanitize($contenu);
    }

    /** HTML à charger dans l'éditeur (même règle que render). */
    public static function forEditor(string $contenu): string
    {
        return self::render($contenu);
    }

    private static function looksLikeHtml(string $contenu): bool
    {
        // Le HTML produit par l'éditeur commence toujours par une balise de bloc
        return (bool) preg_match('/^\s*<(p|h[1-6]|ul|ol|blockquote|pre|div)[\s>]/i', $contenu);
    }

    private static function plainTextToHtml(string $text): string
    {
        $text = trim(str_replace(["\r\n", "\r"], "\n", $text));
        if ($text === '') {
            return '';
        }

        $out = [];
        foreach (preg_split('/\n{2,}/', $text) ?: [] as $block) {
            $block = trim($block);
            if ($block !== '') {
                $out[] = '<p>' . nl2br(htmlspecialchars($block, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'), false) . '</p>';
            }
        }
        return implode("\n", $out);
    }

    // ------------------------------------------------------------------
    //   Parcours du DOM
    // ------------------------------------------------------------------

    private static function children(DOMNode $parent): string
    {
        $out = '';
        foreach (iterator_to_array($parent->childNodes) as $node) {
            $out .= self::node($node);
        }
        return $out;
    }

    private static function node(DOMNode $node): string
    {
        if ($node instanceof DOMText) {
            return htmlspecialchars($node->nodeValue ?? '', ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        if (!($node instanceof DOMElement)) {
            return ''; // commentaires, instructions de traitement...
        }

        $tag = strtolower($node->tagName);

        if (in_array($tag, self::DROPPED, true)) {
            return '';
        }

        $tag = self::RENAMED[$tag] ?? $tag;

        // Balise inconnue : on garde son contenu, pas la balise
        if (!in_array($tag, self::ALLOWED, true)) {
            return self::children($node);
        }

        $attrs = self::attributes($node, $tag);

        // Lien sans adresse valide : on garde le texte, pas la balise
        if ($tag === 'a' && !isset($attrs['href'])) {
            return self::children($node);
        }

        if ($tag === 'img') {
            // Sans source valide, l'image n'a aucun sens
            return isset($attrs['src']) ? '<img' . self::attrString($attrs) . '>' : '';
        }

        if ($tag === 'br') {
            return '<br>';
        }

        return '<' . $tag . self::attrString($attrs) . '>' . self::children($node) . '</' . $tag . '>';
    }

    // ------------------------------------------------------------------
    //   Attributs
    // ------------------------------------------------------------------

    /** @return array<string,string> */
    private static function attributes(DOMElement $el, string $tag): array
    {
        $attrs = [];

        if ($tag === 'a') {
            $href = self::safeUrl($el->getAttribute('href'));
            if ($href !== null) {
                $attrs['href'] = $href;
                if (preg_match('#^https?:#i', $href)) {
                    $attrs['target'] = '_blank';
                    $attrs['rel']    = 'noopener noreferrer';
                }
            }
            return $attrs;
        }

        if ($tag === 'img') {
            $src = trim($el->getAttribute('src'));
            // Uniquement les images envoyées depuis l'admin (jamais de data:, ni d'hôte externe)
            if (preg_match('#^/images/[A-Za-z0-9_./-]+$#', $src) && !str_contains($src, '..')) {
                $attrs['src'] = $src;
            }
            $alt = trim($el->getAttribute('alt'));
            if ($alt !== '') {
                $attrs['alt'] = mb_substr($alt, 0, 200);
            }
            foreach (['width', 'height'] as $dim) {
                $v = trim($el->getAttribute($dim));
                if (preg_match('/^\d{1,4}(px|%)?$/', $v)) {
                    $attrs[$dim] = $v;
                }
            }
            $attrs['loading'] = 'lazy';
            return $attrs;
        }

        if (in_array($tag, self::STYLABLE, true)) {
            $style = self::safeStyle($el->getAttribute('style'));
            if ($style !== '') {
                $attrs['style'] = $style;
            }
        }

        if (in_array($tag, ['p', 'li', 'h2', 'h3', 'h4', 'blockquote', 'ul', 'ol'], true)) {
            $classes = [];
            foreach (preg_split('/\s+/', trim($el->getAttribute('class'))) ?: [] as $c) {
                if (preg_match('/^ql-(indent-[1-8]|align-(center|right|justify))$/', $c)) {
                    $classes[] = $c;
                }
            }
            if ($classes) {
                $attrs['class'] = implode(' ', $classes);
            }
        }

        return $attrs;
    }

    /** @param array<string,string> $attrs */
    private static function attrString(array $attrs): string
    {
        $s = '';
        foreach ($attrs as $name => $value) {
            $s .= ' ' . $name . '="' . htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"';
        }
        return $s;
    }

    /** URL autorisée : http(s), mailto, tel, chemin relatif ou ancre. null sinon. */
    private static function safeUrl(string $url): ?string
    {
        $url = trim($url);
        // Retire espaces et caractères de contrôle (ex. « java\tscript: ») avant de tester le schéma
        $probe = preg_replace('/[\x00-\x20\x7F-\x9F]+/u', '', $url) ?? '';

        if ($probe === '' || strlen($url) > 2000) {
            return null;
        }

        if (preg_match('#^(https?://|mailto:|tel:)#i', $probe)) {
            return $url;
        }
        if (preg_match('#^(/(?!/)|\#)#', $probe)) {
            return $url;
        }
        return null;
    }

    /** Ne garde que les propriétés CSS autorisées, avec valeurs validées. */
    private static function safeStyle(string $style): string
    {
        $out = [];

        foreach (explode(';', $style) as $decl) {
            if (!str_contains($decl, ':')) {
                continue;
            }
            [$prop, $value] = array_map('trim', explode(':', $decl, 2));
            $prop  = strtolower($prop);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            switch ($prop) {
                case 'color':
                case 'background-color':
                    if (self::isColor($value)) {
                        $out[$prop] = $value;
                    }
                    break;

                case 'font-size':
                    if (preg_match('/^\d{1,3}(\.\d{1,2})?(px|em|rem|%)$/', $value)) {
                        $out[$prop] = $value;
                    }
                    break;

                case 'font-family':
                    $key = strtolower(trim($value, " \t\"'"));
                    if (isset(self::FONTS[$key])) {
                        $out[$prop] = self::FONTS[$key];
                    }
                    break;

                case 'text-align':
                    if (preg_match('/^(left|right|center|justify)$/i', $value)) {
                        $out[$prop] = strtolower($value);
                    }
                    break;
            }
        }

        $s = '';
        foreach ($out as $prop => $value) {
            $s .= $prop . ': ' . $value . '; ';
        }
        return trim($s);
    }

    private static function isColor(string $v): bool
    {
        return (bool) preg_match(
            '/^(#[0-9a-f]{3,8}|rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*(,\s*(0|1|0?\.\d+)\s*)?\)|[a-z]{3,20})$/i',
            $v
        );
    }
}
