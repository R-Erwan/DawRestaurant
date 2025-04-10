<?php

namespace controllers;
use Exception;
use PDO;
use services\OpeningExceptionService;
require_once 'services/OpeningExceptionService.php';

class OpeningExceptionController{
    private $openingExceptionService;

    public function __construct(PDO $pdo){
        $this->openingExceptionService = new OpeningExceptionService($pdo);
    }

    public function getOpeningExceptionByDate($date){
        try {
            $result = $this->openingExceptionService->getByDate($date);
            http_response_code(200);
            echo json_encode(["message" => "Opening rules retrieved successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function getAllFuturOpeningsExceptions(){
        try {
            $result = $this->openingExceptionService->getAllFutur();
            http_response_code(200);
            echo json_encode(["message" => "Opening rules retrieved successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function createOpeningException($data){
        if(!isset($data['date']) || !isset($data['open']) || !isset($data['comment'])){
            http_response_code(400);
            echo json_encode(["message" => "Missing fields"]);
            exit;
        }
        try {
            $result = $this->openingExceptionService->create(
                $data['date'],
                $data['open'],
                $data['comment'],
                $data['times'] ?? null);
            http_response_code(200);
            echo json_encode(["message" => "Opening rules created successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function deleteOpeningException(){
        if(!isset($_GET['id_exc'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Parameter id_exc is required']);
            exit;
        }
        try {
            $result = $this->openingExceptionService->deleteById($_GET['id_exc']);
            http_response_code(200);
            echo json_encode(["message" => "Opening Exception rule delted successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

}