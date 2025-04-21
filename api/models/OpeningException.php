<?php

namespace models;

use PDO;

class OpeningException {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $sql = '
            SELECT * FROM exception_rules er
            JOIN exception_time_rules et ON er.id = et.id_exc
            WHERE er.id = ?
            ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllFutur(): array
    {
        $sql = '
            SELECT * FROM exception_rules er
            JOIN exception_time_rules etr on er.id = etr.id_exc
            WHERE date >= CURRENT_DATE ORDER BY date DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByDate(string $date): array
    {
        $sql = '
            SELECT * FROM exception_rules er
            JOIN exception_time_rules et ON er.id = et.id_exc
            WHERE date = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public function getIdByDate(string $date){
        $sql = 'SELECT id FROM exception_rules WHERE date = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchColumn();
    }

    public function createExc(string $date, string $open, string $comment): false|string
    {
        $sql = 'INSERT INTO exception_rules (date, open, comment) VALUES (?, ?, ?)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date, $open, $comment]);
        return $this->pdo->lastInsertId();
    }

    public function createExcTimeRule(int $id_exc, string $time_start, string $time_end, int $nb_places): false|string
    {
        $sql = 'INSERT INTO exception_time_rules (id_exc, time_start, time_end, number_of_places) VALUES (?,?,?,?)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_exc, $time_start, $time_end, $nb_places]);
        return $this->pdo->lastInsertId();
    }

    public function deleteById(int $id): int
    {
        $sql = 'DELETE FROM exception_rules WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

}