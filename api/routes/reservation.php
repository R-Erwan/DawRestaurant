<?php
// routes/reservation.php
// routes/reservation.php

global $pdo;

use controllers\ReservationController;

require_once 'controllers/ReservationController.php';

$reservationController = new ReservationController($pdo);

function getJSON() {
    return json_decode(file_get_contents('php://input'), true);
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        if ($_GET['action'] === 'createReservation') {
            $input = getJSON();
            if ($input) {
                $reservationController->createReservation($input);
            } else {
                http_response_code(400);
                echo json_encode(['Message' => 'Un problème est survenu.']);
            }
        } elseif ($_GET['action'] === 'updateReservation') {
            $input = getJSON();
            if ($input) {
                $reservationController->updateReservation($input);
            } else {
                http_response_code(400);
                echo json_encode(['Message' => 'Un problème est survenu.']);
            }
        }
        break;
    case 'GET':
        $reservationController->getAllReservations();
        break;
    case 'DELETE':
        $input = getJSON();
        if ($input) {
            $reservationController->deleteReservation($input);
        } else {
            http_response_code(400);
            echo json_encode(['Message' => 'Un problème est survenu.']);
        }
        break;
    default:

        http_response_code(405);
        echo json_encode(['Message' => 'Requete non valide']);
        break;
}












