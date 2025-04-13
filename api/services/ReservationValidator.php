<?php

namespace services;

require_once 'OpeningBasicService.php';
require_once 'OpeningExceptionService.php';
require_once 'ReservationService.php';
class ReservationValidator {
    private OpeningBasicService $openingBasicService;
    private OpeningExceptionService $openingExceptionService;
    private ReservationService $reservationService;

    public function __construct(\PDO $pdo){
        $this->openingBasicService = new OpeningBasicService($pdo);
        $this->openingExceptionService = new OpeningExceptionService($pdo);
        $this->reservationService = new ReservationService($pdo);
    }

    public function isValidReservation($reservation_date, $reservation_time, $number_of_people) {
        $exceptionsRules = $this->openingExceptionService->getByDate($reservation_date);


        $guestsCount = $this->reservationService->getNumberOfReservationsByDate($reservation_date);

        // S'il n'y a pas de règles exceptionnelles, on applique les règles de bases
        if(count($exceptionsRules) === 0){

            // Cherche les règles de bases
            try {
                $basicRules = $this->openingBasicService->getByDate($reservation_date);
            } catch (\Exception $e) {
                return false;
            }

            if(count($basicRules) === 0){
                return false; // S'il n'y a pas de règles de base, alors c'est que c'est fermé.
            }
            // Vérification règles de bases



        } else { // Sinon, on applique les règles exceptionnelles
            // Vérification règles exceptionnelles

        }

        var_dump($exceptionsRules);
        var_dump($basicRules);
        die();
    }

}