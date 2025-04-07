<?php

namespace models;

use PDO;

class Reservation
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($user_id, $name, $email, $reservation_date, $reservation_time, $number_of_people, $status): bool
    {
        $sql ="INSERT INTO reservations (user_id, name, email, reservation_date, reservation_time, status, number_of_people) 
                VALUES (:user_id, :name, :email, :reservation_date, :reservation_time, :status, :number_of_people)";


        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
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
        $sql = "SELECT * FROM reservations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserID($user_id)
    {
        $sql = "SELECT * FROM reservations WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserAndStatus($id, $status)
    {
        $sql = "SELECT * FROM reservations WHERE user_id = :user_id AND status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $id);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $reservation_date, $reservation_time, $number_of_people): bool
    {
        $sql = "UPDATE reservations SET
                        reservation_date = :reservation_date,
                        reservation_time = :reservation_time,
                        number_of_people = :number_of_people
                    WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);

        return $stmt->execute();
    }

    public function updateAdmin($id, $name, $email, $reservation_date, $reservation_time, $number_of_people): bool
    {
        $sql = "UPDATE reservations SET
                        name = :name,
                        email = :email,
                        reservation_date = :reservation_date,
                        reservation_time = :reservation_time,
                        number_of_people = :number_of_people
                    WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);
        return $stmt->execute();
    }

    public function delete($id): bool
    {
        $sql = "DELETE FROM reservations WHERE id = :reservation_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

}