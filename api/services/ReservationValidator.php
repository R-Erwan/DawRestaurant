<?php

namespace services;

use DateInterval;
use DateTime;
use Exception;
use InvalidArgumentException;
use PDO;

class ReservationValidator {
    private OpeningBasicService $openingBasicService;
    private OpeningExceptionService $openingExceptionService;
    private ReservationService $reservationService;

    public function __construct(PDO $pdo, ReservationService $reservationService){
        $this->openingBasicService = new OpeningBasicService($pdo);
        $this->openingExceptionService = new OpeningExceptionService($pdo);
        $this->reservationService = $reservationService;
    }

    /**
     * @throws Exception
     */
    public function isValidReservation(string $reservation_date, string $reservation_time, int $number_of_people): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $reservation_date);
        if(!$d || $d->format("Y-m-d") !== $reservation_date){
            throw new InvalidArgumentException("Invalid date format");
        }
        $today = new DateTime();
        $today->setTime(0,0);
        $d->setTime(0,0);
        if($d < $today){
            throw new Exception("Can't make reservation for past day");
        }

        $maxDate = (clone $today)->add(new DateInterval('P3M'));

        if ($d > $maxDate) {
            throw new Exception("Reservations can't be made more than 3 months in advance");
        }

        $exceptionsRules = $this->openingExceptionService->getByDate($reservation_date);

        // S'il n'y a pas de règles exceptionnelles, on applique les règles de bases
        if(count($exceptionsRules) === 0){
            // Cherche les règles de bases
            $basicRules = $this->openingBasicService->getByDate($reservation_date);

            if(count($basicRules) === 0){
                throw new Exception("Closed");
            }

            // Vérification règles de bases
            $time = DateTime::createFromFormat('H:i', $reservation_time);
            if (!$time) {
                $time = DateTime::createFromFormat('H:i:s', $reservation_time);
            }
            foreach ($basicRules as $basicRule) {
                $timeS = DateTime::createFromFormat('H:i:s',$basicRule['time_start']);
                $timeE = DateTime::createFromFormat('H:i:s',$basicRule['time_end']);
                if($time >= $timeS && $time <= $timeE){
                    $guestsCount = $this->reservationService->getNumberOfReservationsByDateAndTimes($reservation_date,$basicRule['time_start'],$basicRule['time_end']);
                    $maxGuests = $basicRule['number_places'];
                    if($guestsCount + $number_of_people <= $maxGuests){
                        return true;
                    } else {
                        throw new Exception("No more places available");
                    }
                }
            }
            throw new Exception("Invalide time"); // Aucun créneaux valide trouvé
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
                        throw new Exception("Exceptional closing : " . $exceptionRule['comment']);
                    }
                    if($guestsCount + $number_of_people <= $maxGuests){
                        return true;
                    } else {
                        throw new Exception("No more places available");
                    }
                }
            }
        }
        return false;
    }

}