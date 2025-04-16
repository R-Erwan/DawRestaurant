<?php

namespace controllers;

use Exception;
use services\MailService;
use services\ReservationService;
use services\UserService;

require_once 'services/ReservationService.php';
require_once 'services/MailService.php';
require_once 'services/UserService.php';

class ReservationController
{
    private ReservationService $reservationService;
    private UserService $userService;

    public function __construct($pdo)
    {
        $this->reservationService = new ReservationService($pdo);
        $this->userService = new UserService($pdo);
    }

    public function createReservation($data,$requestedId): void
    {
        $requiredFields = ["date", "time", "guests"];
        $this->validateData($data, $requiredFields);


        try {
            $user = $this->userService->getById($requestedId);

            $user_id = $requestedId;
            $email = $user["email"];
            $date = $data["date"];
            $time = $data["time"];
            $guests = $data["guests"];
            $this->reservationService->createReservation($user_id, $email, $date, $time, $guests);
            $mailResult = MailService::sendReservationConfirmed($email, $user['name'], $date, $time);
            http_response_code(200);
            echo json_encode(["message" => 'Reservation created successfully ']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
            exit;
        }
    }
    private function validateData($data, $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "Input $field is required"]);
                exit;
            }
        }
    }

    public function getAllReservations(): void
    {
        try {
            $result = $this->reservationService->getAllReservations();
            if ($result) {
                echo json_encode(['message' => 'Reservations retrieved successfully', 'reservations' => $result]);
            } else {
                echo json_encode(['message' => 'No reservations found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error while retrieving reservation', 'error' => $e->getMessage()]);
        }
    }

    public function getReservationByID($id): void
    {
        try {
            $result = $this->reservationService->getReservation($id);
            if ($result) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Reservation retrieved successfully',
                    'reservation' => $result
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No reservations found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error while retrieving reservation', 'error' => $e->getMessage()]);
        }
    }

    public function getReservationByUser($user_id): void
    {
        try {
            $result = $this->reservationService->getReservationByUser($user_id);
            if ($result) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Reservation retrieved successfully',
                    'reservation' => $result
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No reservations found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error while retrieving reservation', 'error' => $e->getMessage()]);
        }
    }

    public function updateReservation($data,$requestedId): void
    {
        $requiredFields = ["time", "guests", "cancel"];
        $this->validateData($data, $requiredFields);

        try {
            $reservation_id = $requestedId;
            $time = $data["time"];
            $guests = $data["guests"];
            $cancel = $data["cancel"];
            $this->reservationService->updateReservation($reservation_id, $time, $guests, $cancel);
            echo json_encode(['message' => 'Reservation updated successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error while updating reservation', 'error' => $e->getMessage()]);
        }
    }

    public function updateReservationAdmin($data,$requestedId): void
    {
        try {
            $reservation_id = $requestedId;
            $date = $data["date"] ?? null;
            $time = $data["time"] ?? null;
            $guests = $data["guests"] ?? null;
            $status = $data["status"] ?? null;

            $this->reservationService->updateReservationAdmin($reservation_id,$date, $time, $guests, $status);
            echo json_encode(['message' => 'Reservation updated successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error while updating reservation', 'error' => $e->getMessage()]);
        }
    }

    public function deleteReservation($id): void
    {
        try {
            $result = $this->reservationService->deleteReservation($id);
            if ($result) {
                echo json_encode(['message' => 'Reservation deleted successfully']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'No reservations found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Error while deleting reservation', 'error' => $e->getMessage()]);
        }
    }
}
