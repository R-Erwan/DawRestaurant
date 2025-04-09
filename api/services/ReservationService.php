<?php

namespace services;

use Exception;
use models\Reservation;
use PDO;

require_once 'models/Reservation.php';
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
    public function createReservation($user_id, $name, $email, $reservation_date, $reservation_time, $number_of_people): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format de l\'email invalide');
        }

        if ($number_of_people >= 9)
        {
            throw new Exception('Le nombre d\'invites est trop éleve);');
        }

        $this->reservation->create($user_id, $name, $email, $reservation_date, $reservation_time, $number_of_people);
    }

    /**
     * @throws Exception
     */
    public function updateReservation($user_id, $reservation_date, $reservation_time, $number_of_people): bool
    {
        if ($number_of_people >= 9)
        {
            throw new Exception('Le nombre d\'invites est trop éleve);');
        }
        return $this->reservation->update($user_id, $reservation_date, $reservation_time, $number_of_people);
    }

    /**
     * @throws Exception
     */
    public function updateReservationAdmin($reservation_id, $name, $email, $reservation_date, $reservation_time, $number_of_people, $status): bool
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception('Format de l\'email invalide');
        }

        if ($number_of_people >= 9)
        {
            throw new Exception('Le nombre d\'invites est trop eleve);');
        }

        return $this->reservation->updateAdmin($reservation_id, $name, $email, $reservation_date, $reservation_time, $number_of_people, $status);

    }

    /**
     * @throws Exception
     */
    public function deleteReservation($reservation_id): bool
    {
        if (!$this->reservation->getByID($reservation_id)) {
            throw new Exception('Reservation non trouvee');
        }
        return $this->reservation->delete($reservation_id);
    }


    /**
     * @throws Exception
     */
    public function getReservation($reservation_id): ?array
    {
        $result = $this->reservation->getByID($reservation_id);
        if (!$result) {
            throw new Exception('Reservation non trouvee');
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
            throw new Exception('Reservation pas trouvee');
        }
        return $result;
    }

    public function getReservationByUser($user_id)
    {
        $result = $this->reservation->getByUserID($user_id);
        if (!$result) {
            throw new Exception('Réservation pas trouvee');
        }
        return $result;
    }

}
