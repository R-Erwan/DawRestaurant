<?php
// routes/reservation.php
// routes/reservation.php

global $pdo;

use controllers\ReservationController;

require_once 'controllers/ReservationController.php';

$reservationController = new ReservationController($pdo);


switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if ($_GET['action'] === 'createReservation') {
            $input = json_decode(file_get_contents('php://input'), true);
            $reservationController->createReservation($input);
        } elseif ($_GET['action'] === 'updateReservation') {
            $input = json_decode(file_get_contents('php://input'), true);
            $reservationController->updateReservation($input);
        }
        break;
    case 'GET':
        $reservationController->getAllReservations();
        break;
    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);
        $reservationController->deleteReservation($input);
        break;
}












