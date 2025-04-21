<?php

// Gérer les requêtes OPTIONS (CORS pré-vol)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once 'config/config.php';
require_once 'config/db.php';

// Normaliser l'URI en supprimant les barres obliques initiales
$requestUri = ltrim($_SERVER['REQUEST_URI'], '/');

// Extraire le chemin sans les paramètres de requête
$path = parse_url($requestUri, PHP_URL_PATH);

// Gérer les routes
switch ($path) {
    case '':
        http_response_code(200);
        echo json_encode(["message" => "Api LeResto operational"]);
        break;
    case 'auth':
        require_once 'routes/auth.php';
        break;
    case 'user':
        require_once 'routes/user.php';
        break;
    case 'announce':
        require_once 'routes/announce.php';
        break;
    case 'dish':
        require_once 'routes/dish.php';
        break;
    case 'subcategory':
        require_once 'routes/subcategory.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Invalid request']);
}
