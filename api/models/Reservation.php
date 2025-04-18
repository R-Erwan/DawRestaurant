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

    public function create(int $user_id, string $name, string $email, string $reservation_date, string $reservation_time, int $number_of_people): bool
    {
        $sql = "INSERT INTO reservations (user_id, name, email, reservation_date, reservation_time, number_of_people) 
                VALUES (:user_id, :name, :email, :reservation_date, :reservation_time, :number_of_people)";


        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':reservation_date', $reservation_date);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);
        return $stmt->execute();
    }

    public function getAll(): array
    {
        $sql = "SELECT r.id, r.user_id, r.reservation_date,
               r.reservation_time, r.number_of_people, r.status,
               r.created_at, u.name, u.email, u.first_name, u.phone_number
               FROM reservations r JOIN users u ON r.user_id = u.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByID(int $id)
    {
        $sql = "SELECT * FROM reservations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserID(int $user_id): array
    {
        $sql = "SELECT * FROM reservations WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchall(PDO::FETCH_ASSOC);
    }

    public function update(int $id, string $reservation_time, int $number_of_people, string $status): bool
    {
        $sql = "UPDATE reservations SET
                        reservation_time = :reservation_time,
                        number_of_people = :number_of_people,
                        status = :status
                    WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':reservation_time', $reservation_time);
        $stmt->bindParam(':number_of_people', $number_of_people);

        return $stmt->execute();
    }

    public function updateAdmin(int $id, string $reservation_date, string $reservation_time, int $number_of_people, string $status): bool
    {
        $fields = [];
        $params = [];

        if ($reservation_date) {
            $fields[] = "reservation_date = ?";
            $params[] = $reservation_date;
        }
        if ($reservation_time) {
            $fields[] = "reservation_time = ?";
            $params[] = $reservation_time;
        }
        if ($number_of_people) {
            $fields[] = "number_of_people = ?";
            $params[] = $number_of_people;
        }
        if ($status) {
            $fields[] = "status = ?";
            $params[] = $status;
        }

        $sql = "UPDATE reservations SET " . implode(", ", $fields) . " WHERE id = ?";
        $params[] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM reservations WHERE id = :reservation_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':reservation_id', $id);
        return $stmt->execute();
    }

    public function getNbPeopleByDate(string $date, string $timeS, string $timeE)
    {
        $sql = "SELECT SUM(number_of_people) AS total_personnes
            FROM reservations
            WHERE reservation_date = ? AND reservation_time BETWEEN ? AND ?
            AND status IN ('confirmed', 'waiting')";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date, $timeS, $timeE]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // On retourne 0 si aucune ligne trouvée ou si la somme est NULL
        return $result['total_personnes'] ?? 0;
    }


}