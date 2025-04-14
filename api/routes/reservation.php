<?php
    // routes/reservation.php

global $pdo;

use controllers\ReservationController;
use middleware\AuthMiddleware;
use services\ReservationService;

require_once 'controllers/ReservationController.php';
require_once 'middleware/AuthMiddleware.php';
require_once 'services/ReservationService.php';

$reservationController = new ReservationController($pdo);
$reservationService = new ReservationService($pdo);

/* MiddleWare */
$authUser = AuthMiddleware::verifyAdminAccesWithoutExit();

/* Public routes */
//  /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $requestedUserId = intval($_GET['id']);
    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId); // Vérifie que c'est le bon user qui demande cette id
    }
    $reservationController->getReservationByUser($_GET['id']);
    exit;
}

// POST /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    $requestedUserId = intval($_GET['id']);
    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId); // Vérifie que c'est le bon user qui demande cette id
    }
    $data = json_decode(file_get_contents('php://input'), true);
    $reservationController->createReservation($data,$requestedUserId);
    exit;
}

// PUT /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id_reservation'])) {
    try {
        $requestedReservation = $reservationService->getReservation($_GET['id_reservation']);
        $requestedUserId = $requestedReservation['user_id'];
    } catch (Exception $e) {
        http_response_code(404);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $reservationController->updateReservation($data,$_GET['id_reservation']);
    exit;
}

/* PROTECTED ROUTE ADMIN ONLY */

// GET /api/reservation
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!$authUser){
        http_response_code(403);
        echo json_encode(["message" => "Unauthorized"]);
        exit;
    }
    $reservationController->getAllReservations();
    exit;
}

// DELETE /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id_reservation'])) {

    try {
        $requestedReservation = $reservationService->getReservation($_GET['id_reservation']);
        $requestedUserId = $requestedReservation['user_id'];
    } catch (Exception $e) {
        http_response_code(404);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId);
    }

    $reservationController->deleteReservation($_GET['id_reservation']);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Invalid reservation endpoint']);












