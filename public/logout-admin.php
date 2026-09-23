<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/AdminAuth.php';

session_start();
AdminAuth::logout();

header('Location: /login-admin.php');
exit;