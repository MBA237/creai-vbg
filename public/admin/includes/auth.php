<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../src/Database.php';
require_once __DIR__ . '/../../../src/AdminAuth.php';

// Redirige vers login si non connecté
if (!AdminAuth::check()) {
    header('Location: /login-admin.php');
    exit;
}

// Rafraîchit les infos en session (au cas où le rôle change en BDD)
$currentAdmin = AdminAuth::user();

// Compte créé ou réinitialisé par quelqu'un d'autre : la personne doit d'abord choisir son propre
// mot de passe. Seule la page « Mon compte » (et la déconnexion) reste accessible d'ici là.
if (AdminAuth::mustChangePassword() && basename($_SERVER['SCRIPT_NAME']) !== 'mon-compte.php') {
    header('Location: /admin/mon-compte.php');
    exit;
}

// Headers de sécurité pour toutes les pages admin
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com;");