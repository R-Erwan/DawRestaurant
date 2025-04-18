<?php

global $pdo;

use middleware\AuthMiddleware;
use controllers\AnnounceController;

$announceController = new AnnounceController($pdo);

/* Routes */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $announceController->getAllAnnounces();
}

/* MiddleWare Auth*/
$authUser = AuthMiddleware::verifyAdminAcces();

// POST /api/announce *
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $announceController->createAnnounce($input);
}

// DELETE /api/announce
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $announceController->deleteAnnounce();
}

// PUT /api/announce?action=positions
if ($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['action']) && $_GET['action'] === 'positions') {
    $input = json_decode(file_get_contents('php://input'), true);
    $announceController->updatePositions($input);
}

//PUT /api/announce
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $announceController->updateAnnounce($input);
}

respond(false, "Invalid announce endpoint", 404);
