<?php

namespace controllers;

use Exception;
use PDO;
use services\OpeningBasicService;

class OpeningBasicController
{
    private OpeningBasicService $openingBasicService;

    public function __construct(PDO $pdo)
    {
        $this->openingBasicService = new OpeningBasicService($pdo);
    }

    public function getOpeningBasicByDate(mixed $data): never
    {
        try {
            $result = $this->openingBasicService->getByDate($data);
            respond(true, "Opening rules retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Can't retrieve Opening rules" . $e->getMessage(), 400);
        }
    }

    public function getAllOpeningsBasic(): never
    {
        try {
            $result = $this->openingBasicService->getAll();
            respond(true, "Opening rules retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Can't retrieve Opening rules" . $e->getMessage(), 400);
        }
    }

    public function createOpeningBasicRule(mixed $data): never
    {
        if (!isset($data['id_day']) || !isset($data['time_start']) || !isset($data['time_end']) || !isset($data['nb_places'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $result = $this->openingBasicService->create($data['id_day'], $data['time_start'], $data['time_end'], $data['nb_places']);
            respond(true, "Opening rules created successfully", 200, ["id" => $result]);
        } catch (Exception $e) {
            respond(false, "Can't create Opening rules" . $e->getMessage(), 400);
        }
    }

    public function updateOpeningBasicRule(mixed $data): never
    {
        if (!isset($data['id_time']) || !isset($data['time_start']) || !isset($data['time_end']) || !isset($data['nb_places'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $this->openingBasicService->updateById($data['id_time'], $data['time_start'], $data['time_end'], $data['nb_places']);
            respond(true, "Opening rules updated successfully");
        } catch (Exception $e) {
            respond(false, "Can't update Opening rules" . $e->getMessage(), 400);
        }
    }

    public function deleteOpeningBasicRule(): never
    {
        if (!isset($_GET['id_time'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $this->openingBasicService->deleteById($_GET['id_time']);
            respond(true, "Opening rules deleted successfully");
        } catch (Exception $e) {
            respond(true, "Can't delete Opening rules" . $e->getMessage(), 400);
        }
    }

}