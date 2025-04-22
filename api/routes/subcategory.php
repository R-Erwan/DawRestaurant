<?php

global $pdo;
use controllers\SubcategoryController;
use middleware\AuthMiddleware;
require_once 'controllers/SubcategoryController.php';
require_once 'middleware/AuthMiddleware.php';

$subcategoryController = new SubcategoryController($pdo);

/* Routes */
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $subcategoryController->getAllSubcategories();
    exit;
}

$authUser = AuthMiddleware::verifyAdminAcces();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $subcategoryController->createSubcategory($input);
}

if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $subcategoryController->updateSubcategory($input);
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $subcategoryController->deleteSubcategory();
    exit;
}