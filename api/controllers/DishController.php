<?php

namespace controllers;

use services\DishService;
require_once 'services/DishService.php';

class DishController
{
    private DishService $dishService;

    public function __construct(\PDO $pdo)
    {
        $this->dishService = new DishService($pdo);
    }

    public function getAllDishes(): void {
        try {
            $result = $this->dishService->getAllDishes();
            http_response_code(200);
            echo json_encode(["message" => "Dishes retrieved successfully", "result" => $result]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function getDishesBySubcategoryId($id): void{
        try {
            $result = $this->dishService->getBySubcategoryId($id);
            http_response_code(200);
            echo json_encode([
                'message' => 'Dishes found',
                'user' => $result
            ]);
        } catch (\Exception $e){
            http_response_code(404);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
}