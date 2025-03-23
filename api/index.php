<?php
// Permet les requêtes venant de n'importe quel domaine (pour développement local)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

// Gérer les requêtes OPTIONS (CORS pré-vol)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

//echo password_hash('admin',PASSWORD_DEFAULT);
require_once __DIR__ . '/vendor/autoload.php';
require_once "config/config.php";
require_once 'config/db.php';
require_once 'routes/auth.php';
