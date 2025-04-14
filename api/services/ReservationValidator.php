<?php

namespace services;

use Cassandra\Date;
use DateTime;

require_once 'OpeningBasicService.php';
require_once 'OpeningExceptionService.php';
require_once 'ReservationService.php';
class ReservationValidator {
    private OpeningBasicService $openingBasicService;
    private OpeningExceptionService $openingExceptionService;
    private ReservationService $reservationService;

    public function __construct(\PDO $pdo, ReservationService $reservationService){
        $this->openingBasicService = new OpeningBasicService($pdo);
        $this->openingExceptionService = new OpeningExceptionService($pdo);
        $this->reservationService = $reservationService;
    }

    /**
     * @throws \Exception
     */
    public function isValidReservation($reservation_date, $reservation_time, $number_of_people): bool
    {
        $exceptionsRules = $this->openingExceptionService->getByDate($reservation_date);

        // S'il n'y a pas de règles exceptionnelles, on applique les règles de bases
        if(count($exceptionsRules) === 0){
            // Cherche les règles de bases
            $basicRules = $this->openingBasicService->getByDate($reservation_date);

            if(count($basicRules) === 0){
                throw new \Exception("Fermeture");
            }

            // Vérification règles de bases
            $time = DateTime::createFromFormat('H:i', $reservation_time);
            foreach ($basicRules as $basicRule) {
                $timeS = DateTime::createFromFormat('H:i:s',$basicRule['time_start']);
                $timeE = DateTime::createFromFormat('H:i:s',$basicRule['time_end']);
                if($time >= $timeS && $time <= $timeE){
                    $guestsCount = $this->reservationService->getNumberOfReservationsByDateAndTimes($reservation_date,$basicRule['time_start'],$basicRule['time_end']);
                    $maxGuests = $basicRule['number_places'];
                    if($guestsCount + $number_of_people <= $maxGuests){
                        return true;
                    } else {
                        throw new \Exception("Plus de places disponibles");
                    }
                }
            }
            throw new \Exception("Horaire invalide"); // Aucun créneaux valide trouvé
        } else { // Sinon, on applique les règles exceptionnelles
            // Vérification règles exceptionnelles
            $time = DateTime::createFromFormat('H:i', $reservation_time);
            foreach ($exceptionsRules as $exceptionRule) {
                $timeS = DateTime::createFromFormat('H:i:s',$exceptionRule['time_start']);
                $timeE = DateTime::createFromFormat('H:i:s',$exceptionRule['time_end']);
                if($time >= $timeS && $time <= $timeE){
                    $guestsCount = $this->reservationService->getNumberOfReservationsByDateAndTimes($reservation_date,$exceptionRule['time_start'],$exceptionRule['time_end']);
                    $maxGuests = $exceptionRule['number_of_places'];
                    if($maxGuests === 0){
                        throw new \Exception("Fermeture exceptionnel : " . $exceptionRule['comment']);
                    }
                    if($guestsCount + $number_of_people <= $maxGuests){
                        return true;
                    } else {
                        throw new \Exception("Plus de places disponibles");
                    }
                }
            }
        }
        return false;
    }

}