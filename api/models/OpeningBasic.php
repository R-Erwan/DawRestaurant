<?php

namespace models;
use PDO;

class OpeningBasic{
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getById(int $id_day): array
    {
        $sql = '
            SELECT * FROM days_rules dr
            JOIN time_rules tr on dr.id = tr.id_days
            WHERE dr.id = ?         
         ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_day]);
        return $stmt->fetchAll();
    }

    public function getRangesById(int $id_day): array
    {
        $sql = 'SELECT time_start, time_end FROM days_rules dr
            JOIN time_rules tr on dr.id = tr.id_days
            WHERE id_days = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_day]);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM days_rules dr JOIN time_rules tr on dr.id = tr.id_days';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(int $id_day, string $time_start, string $time_end, int $nb_places): false|string
    {
        $sql = 'INSERT INTO time_rules (id_days, time_start, time_end, number_places) VALUES(?,?,?,?)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_day,$time_start,$time_end,$nb_places]);
        return $this->pdo->lastInsertId();
    }

    public function updateByTimeId(int $id_time, string $time_start, string $time_end, int $nb_places): int
    {
        $sql = ' UPDATE time_rules SET time_start = ?, time_end = ?, number_places = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$time_start,$time_end,$nb_places,$id_time]);
        return $stmt->rowCount();
    }

    public function delete(int $id_time): bool {
        $sql = 'DELETE FROM time_rules WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_time]);
        return $stmt->rowCount() > 0;
    }

}