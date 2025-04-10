<?php

namespace services;
use models\OpeningBasic;
require_once 'models/OpeningBasic.php';

class OpeningBasicService{

    private $openingBasic;
    public function __construct($pdo){
        $this->openingBasic = new OpeningBasic($pdo);
    }

    /**
     * @throws \Exception
     */
    public function getByDate($date){
        $timestamp = strtotime($date);
        if($timestamp === false){
            throw new \Exception("Date format invalid");
        }
        $dayWeek = date('w', $timestamp);
        return $this->getById($dayWeek);
    }

    /**
     * @throws \Exception
     */
    public function getById($id_day){
        if($id_day < 0 || $id_day > 6){
            throw new \Exception("Invalid ID day");
        }
        $openingBasic = $this->openingBasic->getById($id_day);
        if($openingBasic){
            return $openingBasic;
        }
        throw new \Exception("Opening Basic rules Not Found");
    }

    /**
     * @throws \Exception
     */
    public function getAll(){
        $openingBasic = $this->openingBasic->getAll();
        if($openingBasic){
            return $openingBasic;
        }
        throw new \Exception("Openings Basics rules Not Founds");
    }

    /**
     * @throws \Exception
     */
    public function create($id_day, $time_start, $time_end, $nb_places){
        if($id_day < 0 || $id_day > 6){
            throw new \Exception("Invalid ID day");
        }
        if($nb_places < 0){
            throw new \Exception("Bad number of places, can't be negative");
        }
        try {
            return $this->openingBasic->create($id_day,$time_start,$time_end,$nb_places);
        } catch (\PDOException $e) {
            $code = $e->getCode();
            if($code == '23505'){
                throw new \Exception("Time range already exists for this day");
            } elseif ($code == '23514') {
                throw new \Exception("End time must be after start time");
            } else {
                throw new \Exception("Database Error: " . $e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function updateById($id_time, $time_start, $time_end, $nb_places){
        if($nb_places < 0){
            throw new \Exception("Number of places must be greater than zero");
        }
        try {
             $result = $this->openingBasic->update($id_time,$time_start,$time_end,$nb_places);
             if(!$result){
                 throw new \Exception("No rules found to update");
             }
             return true;
        } catch (\PDOException $e) {
            $code = $e->getCode();
            if($code == '23505'){
                throw new \Exception("Time range already exists for this day");
            } elseif ($code == '23514') {
                throw new \Exception("End time must be after start time");
            } else {
                throw new \Exception("Database Error: " . $e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteById($id_time): bool {
        try {
            $result = $this->openingBasic->delete($id_time);
            if(!$result){
                throw new \Exception("No rules to delete");
            }
            return true;
        } catch (\PDOException $e) {
            throw new \Exception("Database Error: " . $e->getMessage());
        }
    }

}