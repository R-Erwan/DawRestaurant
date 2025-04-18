<?php

namespace controllers;

use Exception;
use PDO;
use services\OpeningExceptionService;

class OpeningExceptionController
{
    private OpeningExceptionService $openingExceptionService;

    public function __construct(PDO $pdo)
    {
        $this->openingExceptionService = new OpeningExceptionService($pdo);
    }

    public function getOpeningExceptionByDate(mixed $data): never
    {
        try {
            $result = $this->openingExceptionService->getByDate($data);
            respond(true, "Opening rules retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Could not retrieve opening rules : " . $e->getMessage(), 400);
        }
    }

    public function getAllFuturOpeningsExceptions(): never
    {
        try {
            $result = $this->openingExceptionService->getAllFutur();
            respond(true, "Opening rules retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Could not retrieve opening rules : " . $e->getMessage(), 400);
        }
    }

    public function createOpeningException(mixed $data): never
    {
        if (!isset($data['date']) || !isset($data['open']) || !isset($data['comment'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $result = $this->openingExceptionService->create(
                $data['date'],
                $data['open'],
                $data['comment'],
                $data['times'] ?? null);
            respond(true, "Opening rules created successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Could not create opening rules : " . $e->getMessage(), 400);
        }
    }

    public function deleteOpeningException(): never
    {
        if (!isset($_GET['id_exc'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $this->openingExceptionService->deleteById($_GET['id_exc']);
            respond(true, "Opening exception rules deleted successfully");
        } catch (Exception $e) {
            respond(false, "Could not delete opening rules : " . $e->getMessage(), 400);
        }
    }

}