<?php

global $pdo;
use controllers\AuthController;
use controllers\UserController;
use middleware\AuthMiddleware;

$userController = new UserController($pdo);

/* MiddleWare Auth*/
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid user ID"]);
    exit;
}
$requestedUserId = intval($_GET['id']);

$authUser = AuthMiddleware::verifyAdminAccesWithoutExit();
if(!$authUser){
    $authUser = AuthMiddleware::verifyUserAccess($requestedUserId);
}
/* Routes */

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'roles'){
    $userController->getUserRolesById($requestedUserId);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userController->getUserInfoById($requestedUserId);
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    $input = json_decode(file_get_contents('php://input'), true);

    $userController->updateUserById($requestedUserId, $input);
}

