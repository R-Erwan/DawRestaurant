<?php

namespace services;

use DateTime;
use Exception;
use InvalidArgumentException;
use models\Reservation;
use models\User;
use PDO;

class ReservationService
{
    private Reservation $reservation;
    private User $user;
    private ReservationValidator $reservationValidator;

    public function __construct(PDO $pdo)
    {
        $this->reservation = new Reservation($pdo);
        $this->user = new User($pdo);
        $this->reservationValidator = new ReservationValidator($pdo, $this);
    }

    /**
     * @throws Exception
     */
    public function createReservation(int $user_id, string $email, string $reservation_date, string $reservation_time, int $number_of_people): bool
    {
        if ($number_of_people >= 9) {
            throw new Exception("Too much guests");
        }

        $user = $this->user->findById($user_id);

        if (!$user) {
            throw new Exception("User not found");
        }

        if ($email !== $user['email']) {
            throw new Exception("Email is not valid");
        }

        $valid = $this->reservationValidator->isValidReservation($reservation_date, $reservation_time, $number_of_people);
        if (!$valid) {
            throw new Exception("Invalid Reservation");
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
    public function updateReservation(int $reservation_id, string $reservation_time, int $number_of_people, bool $cancel): bool
    {
        $ancientReservation = $this->reservation->getById($reservation_id);
        $ancientNumber = $ancientReservation['number_of_people'];
        $newNumberCompute = 0;
        if ($number_of_people > $ancientNumber) {
            // Si on rajoute des gens à la réservation, on ne passe que les rajouts.
            $newNumberCompute = $number_of_people - $ancientNumber;
        }
        if ($cancel) {
            $status = "cancelled";
        } else {
            $status = "waiting";
        }
        $valid = $this->reservationValidator->isValidReservation($ancientReservation['reservation_date'], $reservation_time, $newNumberCompute);
        if (!$valid) {
            throw new Exception("Invalid Reservation");
        }

        return $this->reservation->update($reservation_id, $reservation_time, $number_of_people, $status);
    }

    /**
     * @throws Exception
     */
    public function updateReservationAdmin(int $reservation_id, ?string $reservation_date, ?string $reservation_time, ?int $number_of_people, ?string $status): bool
    {
        return $this->reservation->updateAdmin($reservation_id, $reservation_date, $reservation_time, $number_of_people, $status);
    }

    /**
     * @throws Exception
     */
    public function deleteReservation(int $reservation_id): bool
    {
        $result = $this->reservation->delete($reservation_id);
        if (!$result) {
            throw new Exception("Reservation not found");
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function getReservation(int $reservation_id): ?array
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


    public function getReservationByUser(int $user_id): array
    {
        $result = $this->reservation->getByUserID($user_id);
        if (!$result) {
            return [];
        }
        return $result;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getNumberOfReservationsByDateAndTimes(string $date, string $timeS, string $timeE)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format("Y-m-d") !== $date) {
            throw new InvalidArgumentException("Invalid date format");
        }
        return $this->reservation->getNbPeopleByDate($date, $timeS, $timeE);
    }
}
