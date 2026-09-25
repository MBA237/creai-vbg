<?php
/** Aides d'affichage des partenaires (logo ou monogramme de repli). */

if (!function_exists('partenaire_logo')) {
    /** Balise du logo ; à défaut, un monogramme construit avec les initiales du nom. */
    function partenaire_logo(array $p): string
    {
        $nom = htmlspecialchars((string) $p['nom'], ENT_QUOTES, 'UTF-8');

        if (!empty($p['logo'])) {
            return '<img src="' . htmlspecialchars((string) $p['logo'], ENT_QUOTES, 'UTF-8')
                 . '" alt="Logo ' . $nom . '" loading="lazy">';
        }

        $mots = preg_split('/[\s\-\']+/u', trim((string) $p['nom'])) ?: [];
        $longs = array_values(array_filter($mots, static fn (string $m): bool => mb_strlen($m) > 2));
        $initiales = '';
        foreach (array_slice($longs ?: $mots, 0, 2) as $mot) {
            $initiales .= mb_strtoupper(mb_substr($mot, 0, 1));
        }

        return '<span class="partenaire-monogram" aria-hidden="true">'
             . htmlspecialchars($initiales, ENT_QUOTES, 'UTF-8') . '</span>';
    }
}
