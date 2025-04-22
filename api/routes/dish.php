<?php

global $pdo;
global $path;

use controllers\DishController;
use controllers\SubcategoryController;
use middleware\AuthMiddleware;

$dishController = new DishController($pdo);
$subcategoryController = new SubcategoryController($pdo);



$subPath = substr($path, strlen('dish'));
$subPath = trim($subPath, '/');

switch (true) {
    case $subPath === '':

        // GET /api/dish
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $dishController->getAllDishes();
            exit;
        }

        $authUser = AuthMiddleware::verifyAdminAcces();

        // POST /api/dish
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $dishController->createDish($input);
        }

        // PUT /api/dish
        if($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $input = json_decode(file_get_contents('php://input'), true);
            $dishController->updateDish($input);
            exit;
        }

        // DELETE /api/dish
        if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $dishController->deleteDish();
            exit;
        }

        respond(false,"Invalide dish endpoint",404);

    case $subPath === 'subcategory':

        // GET /api/subcategory
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $subcategoryController->getAllSubcategories();
            exit;
        }

        $authUser = AuthMiddleware::verifyAdminAcces();

        // POST /api/subcategory
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $subcategoryController->createSubcategory($input);
        }

        // PUT /api/subcategory
        if($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $input = json_decode(file_get_contents('php://input'), true);
            $subcategoryController->updateSubcategory($input);
            exit;
        }

        // DELETE /api/subcategory?id_
        if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $subcategoryController->deleteSubcategory();
            exit;
        }

        respond(false,"Invalide dish subcategory endpoint",404);

    default:
        respond(false,"Invalide endpoint",404);
}







