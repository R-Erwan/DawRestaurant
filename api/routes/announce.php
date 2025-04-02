<?php

global $pdo;
use middleware\AuthMiddleware;
use controllers\AnnounceController;
require_once 'controllers/AnnounceController.php';
require_once 'middleware/AuthMiddleware.php';

$announceController = new AnnounceController($pdo);



/* Routes */
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $announceController->getAllAnnouces();
    exit;
}

/* MiddleWare Auth*/
$authUser = AuthMiddleware::verifyAdminAcces();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $announceController->createAnnounce($input);
}

if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $announceController->deleteAnnounce();
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['action']) && $_GET['action'] === 'positions') {
    $input = json_decode(file_get_contents('php://input'), true);
    $announceController->updatePositions($input);
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $announceController->updateAnnounce($input);
    exit;
}