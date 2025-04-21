<?php

global $pdo;
use controllers\DishController;
use middleware\AuthMiddleware;
require_once 'controllers/DishController.php';
require_once 'middleware/AuthMiddleware.php';

$dishController = new DishController($pdo);

/* Routes */
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $dishController->getAllDishes();
    exit;
}

$authUser = AuthMiddleware::verifyAdminAcces();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $dishController->createDish($input);
}

if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $dishController->updateDish($input);
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $dishController->deleteDish();
    exit;
}