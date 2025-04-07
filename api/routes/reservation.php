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
            $data = json_decode(file_get_contents('php://input'), true);
            $reservationController->createReservation($data);
        } elseif ($_GET['action'] === 'updateReservation') {
            $data = json_decode(file_get_contents('php://input'), true);
            $reservationController->updateReservation($data);
        }
        break;
    case 'GET':
        if ($_GET['action'] === 'All') {
            $reservationController->getAllReservations();
        } elseif ($_GET['action'] === 'AllbyUser')
        {
            $reservationController->getReservationByUser($_GET['id']);
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $reservationController->deleteReservation($data);
        break;
}












