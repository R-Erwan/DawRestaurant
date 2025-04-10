<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once 'config/config.php';
require_once 'config/db.php';

$requestUri = ltrim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($requestUri, PHP_URL_PATH);

// Routing principal
switch (true) {
    case $path === '':
        http_response_code(200);
        echo json_encode(["message" => "Api LeResto operational"]);
        break;

    case $path === 'auth':
        require_once 'routes/auth.php';
        break;

    case $path === 'user':
        require_once 'routes/user.php';
        break;

    case $path === 'announce':
        require_once 'routes/announce.php';
        break;

    case str_starts_with($path, 'opening'):
        require_once 'routes/opening.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Invalid request']);
}
