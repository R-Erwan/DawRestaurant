<?php

namespace controllers;
use Exception;
use PDO;
use services\OpeningBasicService;
require_once 'services/OpeningBasicService.php';

class OpeningBasicController{

    private OpeningBasicService $openingBasicService;

    public function __construct(PDO $pdo){
        $this->openingBasicService = new OpeningBasicService($pdo);
    }

    public function getOpeningBasicByDate($date): void {
        try {
            $result = $this->openingBasicService->getByDate($date);
            http_response_code(200);
            echo json_encode(["message" => "Opening rules retrieved successfully", "result" => $result]);
        } catch(Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function getAllOpeningsBasic(): void {
        try {
            $result = $this->openingBasicService->getAll();
            http_response_code(200);
            echo json_encode(["message" => "Opening rules retrieved successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function createOpeningBasicRule($data): void {
        if(!isset($data['id_day']) || !isset($data['time_start']) || !isset($data['time_end']) || !isset($data['nb_places'])){
            http_response_code(400);
            echo json_encode(['message' => 'Missing information']);
            exit;
        }
        try {
            $result = $this->openingBasicService->create($data['id_day'], $data['time_start'], $data['time_end'], $data['nb_places']);
            http_response_code(200);
            echo json_encode(["message" => "Opening rule created successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function updateOpeningBasicRule($data): void {
        if(!isset($data['id_time']) || !isset($data['time_start']) || !isset($data['time_end']) || !isset($data['nb_places'])){
            http_response_code(400);
            echo json_encode(['message' => 'Missing information']);
            exit;
        }
        try {
            $result = $this->openingBasicService->updateById($data['id_time'], $data['time_start'], $data['time_end'], $data['nb_places']);
            http_response_code(200);
            echo json_encode(["message" => "Opening rule updated successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function deleteOpeningBasicRule(): void {
        if(!isset($_GET['id_time'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Parameter id_time is required']);
            exit;
        }
        try {
            $result = $this->openingBasicService->deleteById($_GET['id_time']);
            http_response_code(200);
            echo json_encode(["message" => "Opening rule deleted successfully", "result" => $result]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }


}