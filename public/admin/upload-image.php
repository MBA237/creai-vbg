<?php
declare(strict_types=1);

/**
 * Envoi d'une image depuis l'éditeur de contenu (admin).
 * POST multipart : champ « image » + en-tête X-CSRF-Token.
 * Réponse JSON : {"url": "/images/articles/contenu/..."} ou {"error": "..."}.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../src/Database.php';
require_once __DIR__ . '/../../src/AdminAuth.php';
require_once __DIR__ . '/../../src/ImageUpload.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function json_out(int $status, array $data): never
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(405, ['error' => 'Méthode non autorisée.']);
}

if (!AdminAuth::check()) {
    json_out(401, ['error' => 'Session expirée : reconnectez-vous puis réessayez.']);
}

$token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
    json_out(403, ['error' => 'Jeton de sécurité invalide : rechargez la page.']);
}

if (empty($_FILES['image'])) {
    json_out(400, ['error' => 'Aucune image reçue.']);
}

$result = ImageUpload::store(
    $_FILES['image'],
    __DIR__ . '/../images/articles/contenu',
    '/images/articles/contenu',
    'contenu'
);

if (isset($result['error'])) {
    json_out(422, ['error' => $result['error']]);
}

json_out(200, ['url' => $result['url']]);
