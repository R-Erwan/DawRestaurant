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

    public function create($data)
    {
        $name = $data["name"];
        $email = $data["email"];
        $date = $data["date"];
        $time = $data["time"];
        $guests = $data["guests"];
        $sql = "INSERT INTO reservations (name,email,date,time,guests) VALUES (:name,:email,:date,:time,:guests)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':guests', $guests);
        $stmt->execute();
    }

    public function getAll()
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

    public function update($input)
    {
        $id = $input['id'];
        $name = $input['name'];
        $email = $input['email'];
        $date = $input['date'];
        $time = $input['time'];
        $guests = $input['guests'];
        $status = $input['status'];
        $sql = "UPDATE reservations SET 
                        name = :name, 
                        email = :email, 
                        date = :date, 
                        time = :time, 
                        guests = :guests, 
                        status = :status 
                WHERE reservation_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':guests', $guests);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM reservations WHERE reservation_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

}