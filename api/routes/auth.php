<?php
// routes/auth.php

global $pdo;
global $path;

use controllers\AuthController;
use middleware\AuthMiddleware;


$authController = new AuthController($pdo);

$subPath = substr($path, strlen('auth'));
$subPath = trim($subPath, '/');

switch (true) {
    case $subPath === '':

        // GET /api/auth?action=adminAccess
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'adminAccess') {
            $authUser = AuthMiddleware::verifyAdminAcces();
            respond(true,"Access granted");
        }

        // POST /api/auth?action=login
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'login') {
            $input = json_decode(file_get_contents('php://input'), true);
            $authController->login($input);
        }

        // POST /api/auth?register
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'register') {
            $input = json_decode(file_get_contents('php://input'), true);
            $authController->register($input);
        }

        respond(false,"Invalide auth endpoint",404);
        break;

    case $subPath === 'request-reset-password':

        // POST /auth/request-reset-password
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $authController->resetPasswordEmail($input);
        }
        break;

    case $subPath === 'reset-password':
        // POST /auth/reset-password
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $authController->resetPasswordToken($input);
        }
        break;

    default:
        respond(false,"Invalid auth endpoint",404);
}




