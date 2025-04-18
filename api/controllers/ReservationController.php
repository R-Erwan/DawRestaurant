<?php

namespace controllers;

use Exception;
use PDO;
use services\MailService;
use services\ReservationService;
use services\UserService;

class ReservationController
{
    private ReservationService $reservationService;
    private UserService $userService;

    public function __construct(PDO $pdo)
    {
        $this->reservationService = new ReservationService($pdo);
        $this->userService = new UserService($pdo);
    }

    public function createReservation(mixed $data, int $requestedId): never
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

            MailService::sendReservationConfirmed($email, $user['name'], $date, $time); // Envoi de mail
            respond(true, "Reservation created successfully");
        } catch (Exception $e) {
            respond(false, "Error creating reservation " . $e->getMessage(), 400);
        }
    }

    private function validateData(mixed $data, array $requiredFields): void
    {
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                respond(false, "Missing required field '" . $field . "'", 400);
            }
        }
    }

    public function getAllReservations(): never
    {
        try {
            $result = $this->reservationService->getAllReservations();
            respond(true, "Reservations retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Error getting reservations " . $e->getMessage(), 400);
        }
    }

    /**
     * @internal This function is not used yet but may be used in a future version.
     */
    public function getReservationByID(int $id): never
    {
        try {
            $result = $this->reservationService->getReservation($id);
            respond(true, "Reservation retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Error getting reservation " . $e->getMessage(), 400);
        }
    }

    public function getReservationByUser(int $user_id): never
    {
        try {
            $result = $this->reservationService->getReservationByUser($user_id);
            respond(true, "Reservation retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Error getting reservation " . $e->getMessage(), 400);
        }
    }

    public function updateReservation(mixed $data, int $requestedId): never
    {
        $requiredFields = ["time", "guests", "cancel"];
        $this->validateData($data, $requiredFields);

        try {
            $reservation_id = $requestedId;
            $time = $data["time"];
            $guests = $data["guests"];
            $cancel = $data["cancel"];
            $this->reservationService->updateReservation($reservation_id, $time, $guests, $cancel);
            respond(true, "Reservation updated successfully");
        } catch (Exception $e) {
            respond(false, "Error updating reservation " . $e->getMessage(), 400);
        }
    }

    public function updateReservationAdmin(mixed $data, int $requestedId): never
    {
        try {
            $reservation_id = $requestedId;
            $date = $data["date"] ?? null;
            $time = $data["time"] ?? null;
            $guests = $data["guests"] ?? null;
            $status = $data["status"] ?? null;

            $this->reservationService->updateReservationAdmin($reservation_id, $date, $time, $guests, $status);
            respond(true, "Reservation updated successfully");
        } catch (Exception $e) {
            respond(false, "Error updating reservation " . $e->getMessage(), 400);
        }
    }

    public function deleteReservation(int $id): never
    {
        try {
            $this->reservationService->deleteReservation($id);
            respond(true, "Reservation deleted successfully");
        } catch (Exception $e) {
            respond(false, "Error deleteing reservation " . $e->getMessage(), 400);
        }
    }
}
