<?php

namespace models;

use PDO;

class Reservation
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($user_id, $reservation_date, $reservation_time, $number_of_people, $status): bool
    {
        $sql = "INSERT INTO reservations (user_id, reservation_date, reservation_time, number_of_people, status)
                VALUES (:user_id, :reservation_date, :reservation_time, :number_of_people, :status)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);
        $stmt->bindParam(':status', $status);
        return $stmt->execute();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM reservations";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByID($id)
    {
        $sql = "SELECT * FROM reservations WHERE reservation_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($user_id, $email, $reservation_date, $reservation_time, $number_of_people, $status): bool
    {
        $sql = "UPDATE reservations SET
                        user_id = :user_id,
                        email = :email,
                        reservation_date = :reservation_date,
                        reservation_time = :reservation_time,
                        number_of_people = :number_of_people,
                        status = :status
                WHERE reservation_id = :reservation_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':reservation_id', $reservation_id);
        return $stmt->execute();
    }

    public function delete($reservation_id): bool
    {
        $sql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':reservation_id', $reservation_id);
        return $stmt->execute();
    }
}
?>
