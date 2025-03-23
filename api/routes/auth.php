<?php
// routes/auth.php

global $pdo;
use controllers\AuthController;

require_once 'controllers/AuthController.php';

$authController = new AuthController($pdo);

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'login') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['email']) || !isset($input['password'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Email and password are required']);
        exit;
    }

    $authController->login($input['email'], $input['password']);
    exit;
}


