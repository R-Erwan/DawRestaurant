<?php

namespace controllers;

use Exception;
use services\ReservationService;

require_once 'services/ReservationService.php';

class ReservationController
{
    private ReservationService $reservationService;

    public function __construct($pdo)
    {
        $this->reservationService = new ReservationService($pdo);
    }

    public function createReservation($data): void
    {
        $requiredFields = ["user_id", "name", "email", "date", "time", "guests"];
        $this->validateData($data, $requiredFields);

        try {
            $user_id = $data["user_id"];
            $name = $data["name"];
            $email = $data["email"];
            $date = $data["date"];
            $time = $data["time"];
            $guests = $data["guests"];
            $this->reservationService->createReservation($user_id, $name, $email, $date, $time, $guests);
            echo json_encode(['message' => 'Reservation creee avec succes']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la creation de la reservation', 'error' => $e->getMessage()]);
        }
    }

    private function validateData($data, $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "Le champ $field est requis"]);
                exit;
            }
        }
    }

    public function getAllReservations(): void
    {
        try {
            $result = $this->reservationService->getAllReservations();
            if ($result) {
                echo json_encode(['message' => 'Reservations trouvees', 'reservations' => $result]);
            } else {
                echo json_encode(['message' => 'Aucune reservation trouvee']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la recuperation des reservations', 'error' => $e->getMessage()]);
        }
    }

    public function getReservationByID($id): void
    {
        try {
            $result = $this->reservationService->getReservation($id);
            if ($result) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Reservation trouvee',
                    'reservation' => $result
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Aucune reservation trouvee']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la recuperation de la reservation', 'error' => $e->getMessage()]);
        }
    }

    public function getReservationByUser($user_id): void
    {
        try {
            $result = $this->reservationService->getReservationByUser($user_id);
            if ($result) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Reservation trouvee',
                    'reservation' => $result
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Aucune reservation trouvee']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la recuperation de la reservation', 'error' => $e->getMessage()]);
        }
    }

    public function updateReservation($data): void
    {
        $requiredFields = ["id", "date", "time", "guests"];
        $this->validateData($data, $requiredFields);

        try {
            $reservation_id = $data["id"];
            $date = $data["date"];
            $time = $data["time"];
            $guests = $data["guests"];
            $this->reservationService->updateReservation($reservation_id, $date, $time, $guests);
            echo json_encode(['message' => 'Reservation mise a jour avec succes']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la mise a jour de la reservation', 'error' => $e->getMessage()]);
        }
    }

    public function updateReservationAdmin($data): void
    {
        $requiredFields = ["id", "name", "email", "date", "time", "type", "guests", "status"];
        $this->validateData($data, $requiredFields);

        try {
            $reservation_id = $data["id"];
            $name = $data["name"];
            $email = $data["email"];
            $date = $data["date"];
            $time = $data["time"];
            $type = $data["type"];
            $guests = $data["guests"];
            $status = $data["status"];

            $this->reservationService->updateReservationAdmin($reservation_id, $name, $email, $date, $time, $type,
                                                                $guests, $status);
            echo json_encode(['message' => 'Reservation mise a jour avec succes']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la mise a jour de la reservation', 'error' => $e->getMessage()]);
        }
    }

    public function deleteReservation($id): void
    {
        try {
            $result = $this->reservationService->deleteReservation($id);
            if ($result) {
                echo json_encode(['message' => 'Reservation supprimee avec succes']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Aucune reservation trouvee']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la suppression de la reservation', 'error' => $e->getMessage()]);
        }
    }
}
