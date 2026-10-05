<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

/**
 * RAG interne : retrouve, dans le contenu du site, les extraits utiles pour répondre à une question.
 *
 * Aucun service externe : on découpe le contenu en extraits, on les indexe (BM25, français
 * simplifié : sans accents, sans mots vides, pluriels/suffixes courants ramenés à la racine)
 * et on classe les extraits par pertinence. L'index est un fichier JSON (storage/rag-index.json)
 * reconstruit automatiquement dès qu'une source change (empreinte) ; si le dossier n'est pas
 * inscriptible, l'index est simplement recalculé à la demande.
 *
 * Sources : pages listées dans config/rag.php, articles et événements publiés,
 * numéros d'aide (config/aide.php), faits rédigés à la main (config/rag.php).
 */
final class Rag
{
    private const K1 = 1.5;
    private const B  = 0.75;

    private const STOPWORDS = [
        'le','la','les','un','une','des','du','de','et','ou','a','au','aux','en','dans','par','pour','sur','avec',
        'sans','sous','que','qui','quoi','quel','quelle','quels','quelles','ce','cet','cette','ces','se','sa','son',
        'ses','leur','leurs','nous','vous','ils','elles','il','elle','on','je','tu','me','te','mon','ma','mes','ton',
        'ta','tes','notre','nos','votre','vos','est','sont','etre','suis','es','sommes','etes','ete','avoir','ai',
        'as','avons','avez','ont','fait','faire','peut','peux','peuvent','comment','pourquoi','quand','ou','y','ne',
        'pas','plus','moins','tres','trop','aussi','mais','donc','car','si','alors','bien','tout','tous','toute',
        'toutes','cela','ca','ici','d','l','s','n','c','j','m','t','qu','est-ce','oui','non','merci','bonjour',
        'salut','svp','veux','voudrais','souhaite','puis','pouvez','possible','savoir','dire','parler','sais',
    ];

    private static ?array $cache = null;

    // ------------------------------------------------------------------ recherche

    /**
     * @return array<int, array{titre: string, url: string, texte: string, score: float}>
     */
    public static function search(string $query, ?int $k = null): array
    {
        $cfg = self::config();
        $k ??= (int) $cfg['top_k'];

        $terms = array_values(array_unique(self::tokenize($query)));
        if (!$terms) {
            return [];
        }

        $index = self::index();
        $chunks = $index['chunks'];
        $n = count($chunks);
        if ($n === 0) {
            return [];
        }

        $avg = max(1.0, $index['avg_len']);
        $scores = [];
        foreach ($chunks as $i => $c) {
            $s = 0.0;
            foreach ($terms as $t) {
                $tf = $c['tf'][$t] ?? 0;
                if ($tf === 0) continue;
                $df  = $index['df'][$t] ?? 0;
                $idf = log(1 + ($n - $df + 0.5) / ($df + 0.5));
                $s  += $idf * ($tf * (self::K1 + 1)) / ($tf + self::K1 * (1 - self::B + self::B * $c['len'] / $avg));
            }
            if ($s > 0) $scores[$i] = $s;
        }
        if (!$scores) {
            return [];
        }

        arsort($scores);
        $best = reset($scores);
        if ($best < (float) $cfg['min_score']) {
            return [];
        }

        $out = [];
        foreach ($scores as $i => $s) {
            if ($s < $best * 0.4 || count($out) >= $k) break;
            $c = $chunks[$i];
            $out[] = ['titre' => $c['titre'], 'url' => $c['url'], 'texte' => $c['texte'], 'score' => round($s, 2)];
        }
        return $out;
    }

    // ------------------------------------------------------------------ réponse sans LLM

    /**
     * Réponse extractive : les phrases des meilleurs extraits qui recoupent le mieux la question,
     * remises dans l'ordre du texte d'origine. Rien n'est reformulé ni inventé.
     *
     * @return array{reply: string, found: bool, sources: array<int, array{titre: string, url: string}>}
     */
    public static function answer(string $query, int $maxSentences = 4): array
    {
        $hits = self::search($query, 3);
        if (!$hits) {
            return ['reply' => '', 'found' => false, 'sources' => []];
        }

        $terms = array_flip(array_unique(self::tokenize($query)));
        $index = self::index();
        $n = max(1, count($index['chunks']));

        $cands = [];
        $order = 0;
        foreach ($hits as $rank => $h) {
            // Une ligne qui s'arrête en plein milieu d'une phrase (balise dans le texte) est recollée à la suivante
            $texte = preg_replace('/(?<![.!?…:;])\n(?=\p{Ll})/u', ' ', $h['texte']) ?? $h['texte'];
            foreach (preg_split('/(?<=[.!?…])\s+|\n+/u', $texte) ?: [] as $sentence) {
                $sentence = trim($sentence);
                // Titres et libellés de boutons (courts, sans ponctuation finale) ne font pas une réponse
                if (mb_strlen($sentence) < 25 || (mb_strlen($sentence) < 60 && !preg_match('/[.!?…]$/u', $sentence))) { $order++; continue; }
                $seen = [];
                $score = 0.0;
                foreach (self::tokenize($sentence) as $t) {
                    if (isset($terms[$t]) && !isset($seen[$t])) {
                        $seen[$t] = true;
                        $df = $index['df'][$t] ?? 0;
                        $score += log(1 + ($n - $df + 0.5) / ($df + 0.5));
                    }
                }
                if ($score > 0) {
                    // Les extraits les mieux classés priment à score de phrase égal
                    $cands[] = ['s' => $sentence, 'score' => $score + min(mb_strlen($sentence), 160) / 160 - $rank * 0.1, 'order' => $order];
                }
                $order++;
            }
        }

        if (!$cands) {
            return ['reply' => '', 'found' => false, 'sources' => []];
        }

        usort($cands, static fn ($a, $b) => $b['score'] <=> $a['score']);
        $picked = array_slice($cands, 0, $maxSentences);
        usort($picked, static fn ($a, $b) => $a['order'] <=> $b['order']);

        $lines = [];
        $len = 0;
        foreach ($picked as $p) {
            if ($len + mb_strlen($p['s']) > 700 && $lines) break;
            $lines[] = $p['s'];
            $len += mb_strlen($p['s']);
        }

        // Les phrases retenues (et le titre de la page) doivent couvrir l'essentiel de la question (au moins 60 % de ses mots) :
        // sinon la réponse porterait sur autre chose et on préfère dire qu'on ne sait pas.
        $covered = [];
        foreach (array_merge($lines, [$hits[0]['titre']]) as $l) {
            foreach (self::tokenize($l) as $t) {
                if (isset($terms[$t])) $covered[$t] = true;
            }
        }
        if (count($covered) < (int) ceil(0.6 * count($terms))) {
            return ['reply' => '', 'found' => false, 'sources' => []];
        }

        $sources = [];
        foreach ($hits as $h) {
            $sources[$h['url']] ??= ['titre' => $h['titre'], 'url' => $h['url']];
        }

        return [
            'reply'   => implode("\n", $lines),
            'found'   => true,
            'sources' => array_slice(array_values($sources), 0, 3),
        ];
    }

    // ------------------------------------------------------------------ index

    private static function config(): array
    {
        return require __DIR__ . '/../config/rag.php';
    }

    private static function storageFile(): string
    {
        return __DIR__ . '/../storage/rag-index.json';
    }

    /** Empreinte des sources : change dès qu'une page, un article, un événement ou une config change. */
    private static function fingerprint(): string
    {
        $cfg = self::config();
        $parts = [filemtime(__DIR__ . '/../config/rag.php'), filemtime(__DIR__ . '/../config/aide.php'), filemtime(__FILE__)];
        foreach (array_keys($cfg['pages']) as $file) {
            $path = __DIR__ . '/../public/' . $file;
            $parts[] = is_file($path) ? filemtime($path) : 0;
        }
        $db = Database::soft(static function (): array {
            $conn = (new Database())->connect();
            $out = [];
            foreach (['articles', 'evenements'] as $table) {
                $r = $conn->query("SELECT COUNT(*) AS n, COALESCE(MAX(updated_at), '') AS m FROM $table WHERE statut = 'publie'")->fetch_assoc();
                $out[] = $table . ':' . $r['n'] . ':' . $r['m'];
            }
            return $out;
        }, ['db-indisponible']);

        return md5(implode('|', array_merge($parts, $db)));
    }

    /** @return array{fingerprint: string, chunks: array, df: array, avg_len: float} */
    public static function index(bool $force = false): array
    {
        $fp = self::fingerprint();

        if (!$force && self::$cache !== null && self::$cache['fingerprint'] === $fp) {
            return self::$cache;
        }

        $file = self::storageFile();
        if (!$force && is_file($file)) {
            $stored = json_decode((string) file_get_contents($file), true);
            if (is_array($stored) && ($stored['fingerprint'] ?? '') === $fp) {
                return self::$cache = $stored;
            }
        }

        $index = self::build($fp);
        $dir = dirname($file);
        if ((is_dir($dir) || @mkdir($dir, 0775, true)) && is_writable($dir)) {
            @file_put_contents($file, json_encode($index, JSON_UNESCAPED_UNICODE), LOCK_EX);
        }
        return self::$cache = $index;
    }

    private static function build(string $fingerprint): array
    {
        $chunks = [];
        foreach (self::sources() as $src) {
            foreach (self::split($src['texte'], (int) self::config()['chunk_size']) as $piece) {
                $chunks[] = ['titre' => $src['titre'], 'url' => $src['url'], 'texte' => $piece];
            }
        }

        $df = [];
        $total = 0;
        foreach ($chunks as &$c) {
            // Le titre compte double : une question sur « Solen » doit trouver la page Solen
            $tokens = array_merge(self::tokenize($c['titre']), self::tokenize($c['titre']), self::tokenize($c['texte']));
            $c['tf'] = array_count_values($tokens);
            $c['len'] = count($tokens);
            $total += $c['len'];
            foreach (array_keys($c['tf']) as $t) {
                $df[$t] = ($df[$t] ?? 0) + 1;
            }
        }
        unset($c);

        return [
            'fingerprint' => $fingerprint,
            'built_at'    => date('c'),
            'chunks'      => $chunks,
            'df'          => $df,
            'avg_len'     => $chunks ? $total / count($chunks) : 0.0,
        ];
    }

    // ------------------------------------------------------------------ sources

    /** @return array<int, array{titre: string, url: string, texte: string}> */
    private static function sources(): array
    {
        $cfg = self::config();
        $out = [];

        foreach ($cfg['pages'] as $file => [$titre, $url]) {
            $path = __DIR__ . '/../public/' . $file;
            if (!is_file($path)) continue;
            $texte = self::phpPageToText((string) file_get_contents($path));
            if ($texte !== '') $out[] = ['titre' => $titre, 'url' => $url, 'texte' => $texte];
        }

        foreach ($cfg['faits'] as $f) {
            if (!empty($f['texte'])) {
                $out[] = ['titre' => (string) ($f['titre'] ?? 'Information'), 'url' => (string) ($f['url'] ?? '/'), 'texte' => (string) $f['texte']];
            }
        }

        $aide = self::aideText();
        if ($aide !== '') $out[] = ['titre' => "Numéros d'urgence et ligne d'écoute", 'url' => '/besoin-aide.php', 'texte' => $aide];

        $db = Database::soft(static function (): array {
            $conn = (new Database())->connect();
            $rows = [];
            $r = $conn->query("SELECT titre, slug, extrait, contenu FROM articles WHERE statut = 'publie' ORDER BY COALESCE(publie_le, created_at) DESC LIMIT 300");
            foreach ($r->fetch_all(MYSQLI_ASSOC) as $a) {
                $rows[] = [
                    'titre' => 'Article : ' . $a['titre'],
                    'url'   => '/article.php?slug=' . rawurlencode($a['slug']),
                    'texte' => $a['extrait'] . "\n\n" . self::htmlToText((string) $a['contenu']),
                ];
            }
            $r = $conn->query("SELECT titre, description, contenu, lieu, date_debut, date_fin, organisateur FROM evenements WHERE statut = 'publie' ORDER BY date_debut DESC LIMIT 200");
            foreach ($r->fetch_all(MYSQLI_ASSOC) as $e) {
                $quand = 'Le ' . date('d/m/Y à H\hi', strtotime($e['date_debut']));
                $rows[] = [
                    'titre' => 'Événement : ' . $e['titre'],
                    'url'   => '/actualites.php?type=evenements',
                    'texte' => $quand . ', ' . $e['lieu']
                        . ($e['organisateur'] ? '. Organisé par ' . $e['organisateur'] : '')
                        . ".\n\n" . $e['description'] . "\n\n" . self::htmlToText((string) ($e['contenu'] ?? '')),
                ];
            }
            return $rows;
        }, []);

        return array_merge($out, $db);
    }

    private static function aideText(): string
    {
        $aide = require __DIR__ . '/../config/aide.php';
        $lines = [];
        foreach ($aide['urgences'] ?? [] as $u) {
            $lines[] = "Numéro {$u['numero']} : {$u['libelle']} — {$u['detail']}.";
        }
        $l = $aide['ligne_ecoute'] ?? [];
        if (!empty($l['telephone'])) $lines[] = "Ligne d'écoute du CREAI-VBG : {$l['telephone']}" . (!empty($l['horaires']) ? ' (' . $l['horaires'] . ')' : '') . '.';
        if (!empty($l['whatsapp']))  $lines[] = "WhatsApp : {$l['whatsapp']}.";
        return implode("\n", $lines);
    }

    /** Texte lisible d'un fichier de page : sans code PHP, scripts ni balises. */
    private static function phpPageToText(string $src): string
    {
        $src = preg_replace('/<\?(?:php|=).*?\?>/s', ' ', $src) ?? $src;
        return self::htmlToText($src);
    }

    private static function htmlToText(string $html): string
    {
        $html = preg_replace('#<(script|style|svg|noscript)\b.*?</\1>#is', ' ', $html) ?? $html;
        $html = preg_replace('#<!--.*?-->#s', ' ', $html) ?? $html;
        $html = preg_replace('#<(br|/p|/div|/li|/h[1-6]|/tr|/section|/article|/blockquote)[^>]*>#i', "\n", $html) ?? $html;
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace("\xC2\xA0", ' ', $text);
        $lines = array_filter(array_map(static fn ($l) => trim(preg_replace('/[ \t]+/', ' ', $l) ?? $l), explode("\n", $text)), static fn ($l) => $l !== '');
        return implode("\n", $lines);
    }

    /** Découpe en extraits d'environ $size caractères, en gardant les paragraphes entiers. */
    private static function split(string $text, int $size): array
    {
        $paras = array_values(array_filter(array_map('trim', explode("\n", $text)), static fn ($p) => mb_strlen($p) > 2));
        $chunks = [];
        $cur = '';
        foreach ($paras as $p) {
            if ($cur !== '' && mb_strlen($cur) + mb_strlen($p) > $size) {
                $chunks[] = $cur;
                $cur = '';
            }
            $cur .= ($cur === '' ? '' : "\n") . $p;
        }
        if (mb_strlen($cur) > 20) $chunks[] = $cur;
        return $chunks;
    }

    // ------------------------------------------------------------------ texte

    /** @return string[] racines des mots significatifs */
    public static function tokenize(string $text): array
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = strtr($text, [
            'à'=>'a','â'=>'a','ä'=>'a','á'=>'a','ã'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e',
            'î'=>'i','ï'=>'i','í'=>'i','ô'=>'o','ö'=>'o','ó'=>'o','ù'=>'u','û'=>'u','ü'=>'u','ú'=>'u','ÿ'=>'y','œ'=>'oe','æ'=>'ae',
            '’'=>' ',"'"=>' ',
        ]);
        preg_match_all('/[a-z0-9]+/', $text, $m);
        $out = [];
        foreach ($m[0] as $w) {
            if (in_array($w, self::STOPWORDS, true) || (strlen($w) < 2 && !ctype_digit($w))) continue;
            $out[] = self::stem($w);
        }
        return $out;
    }

    private static function stem(string $w): string
    {
        if (strlen($w) <= 4) return $w;
        foreach (['ements','ement','ations','ation','ites','ite','ances','ance','eurs','eur','euses','euse','ives','ive','if','aux','al','ees','ee','es','s','x','e'] as $suffix) {
            if (str_ends_with($w, $suffix) && strlen($w) - strlen($suffix) >= 4) {
                return substr($w, 0, -strlen($suffix));
            }
        }
        return $w;
    }
}
