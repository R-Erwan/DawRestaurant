<?php

// Gérer les requêtes OPTIONS (CORS pré-vol)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once "config/config.php";
require_once 'config/db.php';
require_once 'routes/auth.php';
