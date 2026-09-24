<?php
// Charge les variables d'environnement depuis .env si présent
$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (!str_contains($line, '=')) continue;

        [$key, $value] = explode('=', $line, 2);
        $key   = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        putenv("$key=$value");
    }
} else {
    // Sans .env, le site tente root@localhost et échoue : on laisse au moins une trace dans les logs.
    // Le fichier doit être à la racine du projet (à côté de ce fichier), pas dans public_html.
    error_log('[environment] Fichier .env introuvable : ' . $envFile);
}

