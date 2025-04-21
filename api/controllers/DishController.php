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

    public function createDish($data): void {
        if(!isset($data['name'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Name is required']);
            exit;
        }

        if(!isset($data['price'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Price is required']);
            exit;
        }

        if(!isset($data['subcategory_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Subcategory is required']);
            exit;
        }

        try {
            $result = $this->dishService->create(
                $data['name'],
                $data['price'],
                $data['subcategory_id'],
                $data['description'] ?? null
            );
            http_response_code(200);
            echo json_encode(["message" => "Dish created successfully", "result" => $result]);
        } catch(\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
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

    public function updateDish($data): void {
        if(!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Id is required']);
            exit;
        }
        try {
            $result = $this->dishService->updateById(
                $data['id'],
                $data['name'] ?? null,
                $data['description'] ?? null,
                $data['price'] ?? null,
                $date['subcategory_id'] ?? null
            );
            http_response_code(200);
            echo json_encode(["message" => "Dish updated successfully"]);
        } catch (\Exception $e){
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function deleteDish(): void {
        if(!isset($_GET['dish_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'dish_id is required']);
        }
        try {
            $this->dishService->deleteDish($_GET['dish_id']);
            http_response_code(200);
            echo json_encode(['message' => 'Dish deleted successfully']);
        } catch(\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}