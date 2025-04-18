<?php

namespace services;
use DateTime;
use models\OpeningException;
use PDOException;


class OpeningExceptionService {

    private $openingException;
    public function __construct($pdo) {
        $this->openingException = new OpeningException($pdo);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getByDate($date){
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if(!$d || $d->format("Y-m-d") !== $date){
            throw new \InvalidArgumentException("Invalid date format");
        }
        return $this->openingException->getByDate($date);
    }

    public function getAll(){
        return $this->openingException->getAll();
    }

    public function getAllFutur(){
        return $this->openingException->getAllFutur();
    }

    /**
     * @throws \Exception
     */
    public function create($date, $open, $comment, $times = null){
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if(!$d || $d->format("Y-m-d") !== $date){
            throw new \InvalidArgumentException("Invalid date format");
        }
        $today = new DateTime();
        if($d < $today){
            throw new \InvalidArgumentException("Invalid date");
        }

        if(strlen($comment) > 1024){
            throw new \InvalidArgumentException("Comment cannot be longer than 1024 characters");
        }
        if($open){
            if (!is_array($times)) {
                throw new \InvalidArgumentException("Times must be an array");
            }

            if (count($times) === 0) {
                throw new \InvalidArgumentException("Times must contain at least one entry");
            }

            foreach ($times as $time) {
                if (!is_array($time)) {
                    throw new \InvalidArgumentException("Each time entry must be an array");
                }

                // Vérifier que les clés time_start, time_end et nb_places existent
                if (!isset($time['time_start'], $time['time_end'], $time['nb_places'])) {
                    throw new \InvalidArgumentException("Each time entry must have time_start, time_end, and nb_places");
                }

                // Vérifier que time_start et time_end sont des chaînes valides de type TIME
                $timeStart = DateTime::createFromFormat('H:i', $time['time_start']);
                $timeEnd = DateTime::createFromFormat('H:i', $time['time_end']);
                if (!$timeStart || !$timeEnd) {
                    throw new \InvalidArgumentException("Invalid time format");
                }

                // Vérifier que nb_places est un entier positif
                if (!is_int($time['nb_places']) || $time['nb_places'] < 0) {
                    throw new \InvalidArgumentException("nb_places must be a positive integer");
                }
            }

            // Vérification des chevauchements
            $intervals = array_map(function($time) {
                return [
                    'start' => DateTime::createFromFormat('H:i', $time['time_start']),
                    'end' => DateTime::createFromFormat('H:i', $time['time_end'])
                ];
            }, $times);

            // Tri des intervalles par heure de début
            usort($intervals, function($a, $b) {
                return $a['start'] <=> $b['start'];
            });

            // Vérifie s'il y a chevauchement entre intervalles consécutifs
            for ($i = 0; $i < count($intervals) - 1; $i++) {
                if ($intervals[$i]['end'] > $intervals[$i + 1]['start']) {
                    throw new \InvalidArgumentException("Time intervals must not overlap");
                }
            }

        }

        try {
            $strbool = $open ? 'true' : 'false';
            $exc_id = $this->openingException->createExc($date,$strbool,$comment);
            if(!$open){
                $this->openingException->createExcTimeRule($exc_id,"00:00","23:59",0); // A tester
                return $exc_id;
            }
            try {
                foreach ($times as $time) {
                    $this->openingException->createExcTimeRule($exc_id,$time["time_start"],$time["time_end"],$time["nb_places"]);
                }
            } catch (PDOException $e) {
                $this->openingException->deleteById($exc_id);
                throw new \Exception("Database Error" . $e->getMessage());
            }
            return $exc_id;
        } catch (\PDOException $e) {
            throw new \Exception("Database Error" . $e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteById($id){
        try {
            $result = $this->openingException->deleteById($id);
            if(!$result){
                throw new \Exception("No rule to delete");
            }
            return true;
        } catch (\PDOException $e) {
            throw new \Exception("Database Error");
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteByDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if(!$d || $d->format("Y-m-d") !== $date){
            throw new \InvalidArgumentException("Invalid date format");
        }
        $id = $this->openingException->getIdByDate($date);
        if(!$id){
            throw new \Exception("No rule to delete");
        }
        $this->openingException->deleteById($id);
        return true;
    }
}