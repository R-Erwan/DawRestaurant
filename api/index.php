<?php

// Gérer les requêtes OPTIONS (CORS pré-vol)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

//Pour la production, cela evite tout les messages d'erreur que l'on veut surtout pas envoyer aux clients
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

require_once __DIR__ . '/vendor/autoload.php';
require_once "config/config.php";
require_once 'config/db.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
switch ($requestUri) {
    case '/':
        http_response_code(200);
        echo json_encode(["message" => "Api LeResto operational"]);
        break;
    case '/auth':
        require_once 'routes/auth.php';
        break;
    case '/user':
        require_once 'routes/user.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Invalid request']);
}



