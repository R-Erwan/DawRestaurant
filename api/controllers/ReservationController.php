<?php

namespace controllers;

use Exception;
use models\Reservation;

class ReservationController
{
    private Reservation $reservation;

    public function __construct($pdo)
    {
        $this->reservation = new Reservation($pdo);
    }

    public function createReservation($data): void
    {
        $this->reservation->create($data);
    }

    public function getAllReservations(): void
    {
        try {
            $result = $this->reservation->getAll();
            http_response_code(200);
            echo json_encode(['message' => "Reservations found", 'reservations' => $result]);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["Message" => $e->getMessage()]);
        }

    }
    public function getReservationById($id): void
    {
        try {
            $result = $this->reservation->getById($id);
            http_response_code(200);
            echo json_encode(['message' => "Reservation found", 'reservations' => $result]);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode(["Message" => $e->getMessage()]);
        }

    }
    public function updateReservation($data): void
    {
        $this->reservation->update($data);
    }

    public function deleteReservation($id): void
    {
        $this->reservation->delete($id);
    }
}