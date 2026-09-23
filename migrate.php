<?php
declare(strict_types=1);

require __DIR__ . '/src/Migration.php';

$command = $argv[1] ?? 'up';
$migration = new Migration();

match ($command) {
    'up'     => $migration->up(),
    'status' => $migration->status(),
    default  => print("Usage : php migrate.php [up|status]\n"),
};