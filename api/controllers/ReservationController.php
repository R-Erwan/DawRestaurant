<?php

namespace controllers;

use Exception;
use services\ReservationService;

class ReservationController
{
    private ReservationService $reservationService;

    public function __construct($pdo)
    {
        $this->reservationService = new ReservationService($pdo);
    }

    public function createReservation($data): void
    {
        try {
            if (empty($data["name"]) || empty($data["email"]) || empty($data["date"]) || empty($data["time"]) || empty($data["guests"])) {
                http_response_code(400);
                echo json_encode(['message' => 'Données manquantes']);
                exit;
            }
            $this->reservationService->createReservation(
                $data["name"],
                $data["email"],
                $data["date"],
                $data["time"],
                $data["guests"]
            );
            echo json_encode(['message' => 'Réservation créée avec succès']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la création de la réservation', 'error' => $e->getMessage()]);
        }
    }

    public function getAllReservations(): void
    {
        try {
            $result = $this->reservationService->getAllReservations();
            if ($result) {
                echo json_encode(['message' => 'Réservations trouvées', 'reservations' => $result]);
            } else {
                echo json_encode(['message' => 'Aucune réservation trouvée']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la récupération des réservations', 'error' => $e->getMessage()]);
        }
    }

    public function getReservationById($id): void
    {
        try {
            $result = $this->reservationService->getReservation($id);
            if ($result) {
                http_response_code(200);
                echo json_encode([
                    'message' => 'Réservation trouvée',
                    'reservation' => $result
                ]);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Aucune réservation trouvée']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la récupération de la réservation', 'error' => $e->getMessage()]);
        }
    }

    public function updateReservation($data): void
    {
        try {
            if (empty($data["id"]) || empty($data["name"]) || empty($data["email"]) || empty($data["date"]) || empty($data["time"]) || empty($data["guests"])) {
                http_response_code(400);
                echo json_encode(['message' => 'Données manquantes']);
                exit;
            }
            $this->reservationService->updateReservation(
                $data["id"],
                $data["name"],
                $data["email"],
                $data["date"],
                $data["time"],
                $data["guests"]
            );

            echo json_encode(['message' => 'Réservation mise à jour avec succès']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la mise à jour de la réservation', 'error' => $e->getMessage()]);
        }
    }

    public function deleteReservation($id): void
    {
        try {
            $result = $this->reservationService->deleteReservation($id);
            if ($result) {
                echo json_encode(['message' => 'Réservation supprimée avec succès']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Aucune réservation trouvée']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Erreur lors de la suppression de la réservation', 'error' => $e->getMessage()]);
        }
    }
}
