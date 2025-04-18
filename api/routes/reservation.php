<?php
    // routes/reservation.php

global $pdo;

use controllers\ReservationController;
use middleware\AuthMiddleware;
use services\ReservationService;


$reservationController = new ReservationController($pdo);
$reservationService = new ReservationService($pdo);

/* MiddleWare */
$authUser = AuthMiddleware::verifyAdminAccesWithoutExit();

/* Public routes */

//  /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $requestedUserId = intval($_GET['id']);
    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId); // Vérifie que c'est le bon utilisateur qui demande cette id
    }
    $reservationController->getReservationByUser($_GET['id']);
}

// POST /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id'])) {
    $requestedUserId = intval($_GET['id']);
    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId); // Vérifie que c'est le bon utilisateur qui demande cette id
    }
    $data = json_decode(file_get_contents('php://input'), true);
    $reservationController->createReservation($data,$requestedUserId);
}

// PUT /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'PUT' && isset($_GET['id_reservation'])) {
    try {
        $requestedReservation = $reservationService->getReservation($_GET['id_reservation']);
        $requestedUserId = $requestedReservation['user_id'];
    } catch (Exception $e) {
        respond(false,"Invalid request : " . $e->getMessage(),404);
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId);
        $reservationController->updateReservation($data,$_GET['id_reservation']);
    } else {
        $reservationController->updateReservationAdmin($data,$_GET['id_reservation']);
    }
}

/* PROTECTED ROUTE ADMIN ONLY */

// GET /api/reservation
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!$authUser){
        respond(false,"Unauthorized", 403);
    }
    $reservationController->getAllReservations();
}

// DELETE /api/reservation?id=
if($_SERVER['REQUEST_METHOD'] == 'DELETE' && isset($_GET['id_reservation'])) {

    try {
        $requestedReservation = $reservationService->getReservation($_GET['id_reservation']);
        $requestedUserId = $requestedReservation['user_id'];
    } catch (Exception $e) {
        respond(false,"Invalid request : " . $e->getMessage(),404);
    }
    if(!$authUser){
        $authUser = AuthMiddleware::verifyUserAccess($requestedUserId);
    }
    $reservationController->deleteReservation($_GET['id_reservation']);
}

respond(false,"Invalid reservation endpoint",404);












