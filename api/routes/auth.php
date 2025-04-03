<?php
// routes/auth.php

global $pdo;

use controllers\AuthController;
use controllers\ReservationController;

require_once 'controllers/AuthController.php';

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







