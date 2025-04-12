<?php

namespace models;
class OpeningBasic{
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getById($id_day) {
        $sql = '
            SELECT * FROM days_rules dr
            JOIN time_rules tr on dr.id = tr.id_days
            WHERE dr.id = ?         
         ';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_day]);
        return $stmt->fetchAll();
    }

    public function getRangesById($id_day) {
        $sql = 'SELECT time_start, time_end FROM days_rules dr
            JOIN time_rules tr on dr.id = tr.id_days
            WHERE id_days = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_day]);
        return $stmt->fetchAll();
    }

    public function getAll() {
        $sql = 'SELECT * FROM days_rules dr JOIN time_rules tr on dr.id = tr.id_days';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($id_day,$time_start,$time_end, $nb_places) {
        $sql = 'INSERT INTO time_rules (id_days, time_start, time_end, number_places) VALUES(?,?,?,?)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_day,$time_start,$time_end,$nb_places]);
        return $this->pdo->lastInsertId();
    }

    public function updateByTimeId($id_time, $time_start, $time_end, $nb_places) {
        $sql = ' UPDATE time_rules SET time_start = ?, time_end = ?, number_places = ? WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$time_start,$time_end,$nb_places,$id_time]);
        return $stmt->rowCount();
    }

    public function updateOpenByDayId(){
        ; //TODO
    }

    public function delete($id_time): bool {
        $sql = 'DELETE FROM time_rules WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_time]);
        return $stmt->rowCount() > 0;
    }

}