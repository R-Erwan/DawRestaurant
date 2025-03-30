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

    public function create($data): void
    {
        $user_id = $data["user_id"];
        $reservation_date = $data["reservation_date"];
        $reservation_time = $data["reservation_time"];
        $number_of_people = $data["number_of_people"];
        $status = isset($data["status"]) ? $data["status"] : 'en attente';

        $sql = "INSERT INTO reservations (user_id, reservation_date, reservation_time, number_of_people, status)
                VALUES (:user_id, :reservation_date, :reservation_time, :number_of_people, :status)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
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

    public function update($input): void
    {
        $reservation_id = $input['reservation_id'];
        $user_id = $input['user_id'];
        $reservation_date = $input['reservation_date'];
        $reservation_time = $input['reservation_time'];
        $number_of_people = $input['number_of_people'];
        $status = $input['status'];

        $sql = "UPDATE reservations SET
                        user_id = :user_id,
                        reservation_date = :reservation_date,
                        reservation_time = :reservation_time,
                        number_of_people = :number_of_people,
                        status = :status
                WHERE reservation_id = :reservation_id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':reservation_id', $reservation_id);
        $stmt->execute();
    }

    public function delete($reservation_id): void
    {
        $sql = "DELETE FROM reservations WHERE reservation_id = :reservation_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':reservation_id', $reservation_id);
        $stmt->execute();
    }
}
?>
