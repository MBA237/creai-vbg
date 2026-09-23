<?php
declare(strict_types=1);

/**
 * Script CLI pour créer un admin.
 * Usage : php create-admin.php email@exemple.com "MotDePasse" "Nom Complet" super_admin
 */

if (PHP_SAPI !== 'cli') {
    die('Ce script doit être exécuté en ligne de commande.');
}

require __DIR__ . '/src/Database.php';

$email     = $argv[1] ?? null;
$password  = $argv[2] ?? null;
$nom       = $argv[3] ?? 'Administrateur';
$role      = $argv[4] ?? 'super_admin';

if (!$email || !$password) {
    echo "Usage : php create-admin.php <email> <password> [nom] [role]\n";
    echo "Roles : super_admin, admin, editeur\n";
    exit(1);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Erreur : email invalide.\n";
    exit(1);
}

if (strlen($password) < 12) {
    echo "Erreur : le mot de passe doit faire au moins 12 caractères.\n";
    exit(1);
}

if (!in_array($role, ['super_admin', 'admin', 'editeur'], true)) {
    echo "Erreur : rôle invalide.\n";
    exit(1);
}

$hash = password_hash($password, defined("PASSWORD_ARGON2ID") ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT);

try {
    $conn = (new Database())->connect();

    $stmt = $conn->prepare(
        "INSERT INTO admins (email, password_hash, nom, role) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('ssss', $email, $hash, $nom, $role);
    $stmt->execute();
    $stmt->close();

    echo "✅ Admin créé avec succès :\n";
    echo "   Email : $email\n";
    echo "   Nom   : $nom\n";
    echo "   Rôle  : $role\n";
} catch (mysqli_sql_exception $e) {
    if (str_contains($e->getMessage(), 'Duplicate')) {
        echo "Erreur : cet email existe déjà.\n";
    } else {
        echo "Erreur BDD : " . $e->getMessage() . "\n";
    }
    exit(1);
}