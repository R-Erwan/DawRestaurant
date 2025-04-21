<?php

global $pdo;
use controllers\DishController;
use middleware\AuthMiddleware;
require_once 'controllers/DishController.php';
require_once 'middleware/AuthMiddleware.php';

$dishController = new DishController($pdo);

/* Routes */
// GET /api/dish
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $dishController->getAllDishes();
    exit;
}