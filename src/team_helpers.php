<?php
declare(strict_types=1);

if (!function_exists('initiales')) {
    /** Initiales (2 max) affichées quand un membre n'a pas de photo. */
    function initiales(string $nom): string
    {
        $mots = preg_split('/\s+/', trim($nom), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $init = '';
        foreach (array_slice($mots, 0, 2) as $mot) {
            $init .= mb_strtoupper(mb_substr($mot, 0, 1));
        }
        return $init !== '' ? $init : '?';
    }
}
