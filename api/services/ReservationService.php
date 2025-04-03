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
    public function createReservation($user_id, $name, $email, $reservation_date, $reservation_time, $number_of_people): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format de l\'email invalide');
        }

        if ($number_of_people >= 9)
        {
            throw new Exception('Le nombre d\'invités est trop élevé);');
        }

        return $this->reservation->create($user_id, $name, $email, $reservation_date, $reservation_time, $number_of_people);
    }

    /**
     * @throws Exception
     */
    public function updateReservation($user_id, $reservation_date, $reservation_time, $number_of_people): bool
    {
        if ($number_of_people >= 9)
        {
            throw new Exception('Le nombre d\'invités est trop élevé);');
        }

        return $this->reservation->update($user_id, $reservation_date, $reservation_time, $number_of_people);
    }

    /**
     * @throws Exception
     */
    public function updateReservationAdmin($user_id, $name, $email, $reservation_date, $reservation_time, $number_of_people): bool
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception('Format de l\'email invalide');
        }

        if ($number_of_people >= 9)
        {
            throw new Exception('Le nombre d\'invités est trop élevé);');
        }

        return $this->reservation->updateAdmin($user_id, $name, $email, $reservation_date, $reservation_time, $number_of_people);

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


    /**
     * @throws Exception
     */
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

    public function getReservationByUserID($user_id)
    {
        $result = $this->reservation->getByUserID($user_id);
        if (!$result) {
            throw new Exception('Réservation pas trouvée');
        }
        return $result;
    }
}
