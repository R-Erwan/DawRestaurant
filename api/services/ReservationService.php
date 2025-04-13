<?php

namespace services;

use DateTime;
use Exception;
use models\Reservation;
use models\User;
use PDO;

require_once 'models/Reservation.php';
require_once 'models/User.php';
require_once 'ReservationValidator.php';

class ReservationService
{
    private Reservation $reservation;
    private User $user;
    private ReservationValidator $reservationValidator;

    public function __construct(PDO $pdo)
    {
        $this->reservation = new Reservation($pdo);
        $this->user = new User($pdo);
        $this->reservationValidator = new ReservationValidator($pdo);
    }

    /**
     * @throws Exception
     */
    public function createReservation($user_id, $email, $reservation_date, $reservation_time, $number_of_people): bool
    {
        if ($number_of_people >= 9) {
            throw new Exception('Le nombre d\'invités est trop élevé');
        }

        $user = $this->user->findById($user_id);

        if (!$user) {
            throw new Exception('Utilisateur introuvable');
        }

        if ($email !== $user['email']) {
            throw new Exception('L\'email n\'est pas valide');

        }

        if(!$this->reservationValidator->isValidReservation($reservation_date, $reservation_time, $number_of_people)){
            throw new Exception('Invalid reservation');
        }

         return $this->reservation->create(
            $user_id,
            $user['name'],
            $email,
            $reservation_date,
            $reservation_time,
            $number_of_people
        );
    }

    /**
     * @throws Exception
     */
    public function updateReservation($reservation_id, $reservation_date, $reservation_time, $number_of_people): bool
    {
        if ($number_of_people >= 9) {
            throw new Exception('Le nombre d\'invites est trop élevé');
        }
        return $this->reservation->update($reservation_id, $reservation_date, $reservation_time, $number_of_people);
    }

    /**
     * @throws Exception
     */
    public function updateReservationAdmin($reservation_id, $name, $email, $reservation_date, $reservation_time, $number_of_people, $status): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Format de l\'email invalide');
        }

        if ($number_of_people >= 9) {
            throw new Exception('Le nombre d\'invites est trop élevé');
        }

        return $this->reservation->updateAdmin($reservation_id, $name, $email, $reservation_date, $reservation_time, $number_of_people, $status);
    }

    /**
     * @throws Exception
     */
    public function deleteReservation($reservation_id): bool
    {
        if (!$this->reservation->getByID($reservation_id)) {
            throw new Exception('Réservation non trouvée');
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
            throw new Exception('Réservation non trouvée');
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
            throw new Exception('Aucune réservation trouvée');
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public function getReservationByUser($user_id): array
    {
        $result = $this->reservation->getByUserID($user_id);
        if (!$result) {
            throw new Exception('Réservation non trouvée');
        }
        return $result;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getNumberOfReservationsByDate($date){
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if(!$d || $d->format("Y-m-d") !== $date){
            throw new \InvalidArgumentException("Invalid date format");
        }
        return $this->reservation->getNbPeopleByDate($date);
    }
}
