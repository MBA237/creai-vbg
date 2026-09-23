<?php
declare(strict_types=1);

session_start();

header('Content-Type: application/json; charset=utf-8');

// ---------- Méthode HTTP ----------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée.']);
    exit;
}

// ---------- Lecture du body JSON ----------
$raw  = file_get_contents('php://input');
$data = json_decode((string) $raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Requête invalide.']);
    exit;
}

// ---------- Validation CSRF ----------
if (empty($_SESSION['csrf'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Session invalide.']);
    exit;
}

$token = (string) ($data['csrf'] ?? '');
if (!hash_equals($_SESSION['csrf'], $token)) {
    http_response_code(403);
    echo json_encode(['error' => 'Token CSRF invalide.']);
    exit;
}

// ---------- Validation du message ----------
$message = trim((string) ($data['message'] ?? ''));

if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Message vide.']);
    exit;
}

if (mb_strlen($message) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Message trop long (1000 caractères max).']);
    exit;
}

// ---------- Historique ----------
$history = $data['history'] ?? [];
if (!is_array($history)) {
    $history = [];
}

// ---------- Appel IA ----------
require __DIR__ . '/../../src/AiService.php';

try {
    $ai     = new AiService();
    $result = $ai->send($message, $history);

    echo json_encode([
        'reply'  => $result['reply'],
        'urgent' => $result['urgent'],
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log('[chat.php] ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'error'  => 'Erreur interne.',
        'reply'  => "Une erreur est survenue. En cas d'urgence, appelez le 112.",
        'urgent' => true,
    ], JSON_UNESCAPED_UNICODE);
}