<?php
// routes/auth.php

global $pdo;

use controllers\AuthController;
use middleware\AuthMiddleware;

require_once 'controllers/AuthController.php';
require_once 'middleware/AuthMiddleware.php';

$authController = new AuthController($pdo);

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'login') {
    $input = json_decode(file_get_contents('php://input'), true);
    $authController->login($input);
    exit;
}
// Create User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'register') {
    $input = json_decode(file_get_contents('php://input'), true);
    $authController->register($input);
    exit;
}

/* MiddleWare Auth*/
//Verif Admin
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'adminAccess') {
    $authUser = AuthMiddleware::verifyAdminAcces();
    http_response_code(200);
    echo json_encode(["message" => "Access granted!"]);
}







