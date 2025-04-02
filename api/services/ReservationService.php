<?php

namespace services;

use Exception;
use models\Reservation;
use PDO;

class ReservationService
{
    private Reservation $reservation;

    public function __construct(PDO $pdo)
    {
        $this->reservation = new Reservation($pdo);
    }

    /**
     * @throws Exception
     */
    public function createReservation($name, $email, $date, $time, $guests): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format de lemail invalide');
        }
        return $this->reservation->create($name, $email, $date, $time, $guests);
    }

    /**
     * @throws Exception
     */
    public function updateReservation($user_id, $email, $reservation_date, $reservation_time, $number_of_people, $status): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format de lemail invalide');
        }
        return $this->reservation->update($user_id, $email, $reservation_date, $reservation_time, $number_of_people, $status);
    }

    /**
     * @throws Exception
     */
    public function deleteReservation($idReservation): bool
    {
        if (!$this->reservation->getByID($idReservation)) {
            throw new Exception('Reservation pas trouvée');
        }
        return $this->reservation->delete($idReservation);
    }


    public function getReservation(int $idReservation): ?array
    {
        $result = $this->reservation->getByID($idReservation);
        if (!$result) {
            throw new Exception('Réservation pas trouvée');
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function getAllReservations(): array
    {
        $result = $this->reservation->getAll();

        if (!$result) {
            throw new Exception('Réservation pas trouvée');
        }
        return $result;
    }
}
