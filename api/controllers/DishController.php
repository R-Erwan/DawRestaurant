<?php

namespace controllers;

use Exception;
use PDO;
use services\DishService;

class DishController
{
    private DishService $dishService;

    public function __construct(PDO $pdo)
    {
        $this->dishService = new DishService($pdo);
    }

    public function createDish(mixed $data): never
    {
        if (!isset($data['name']) || !isset($data['price']) || !isset($data['subcategory_id'])) {
            respond(false, "Missing required fields", 400);
        }

        try {
            $result = $this->dishService->create(
                $data['name'],
                $data['price'],
                $data['subcategory_id'],
                $data['description'] ?? null
            );
            respond(true, "Dish created successfully", 200, ["id" => $result]);
        } catch (Exception $e) {
            respond(false, "Failed to create dish" . $e->getMessage(), 400);
        }
    }

    public function getAllDishes(): never
    {
        try {
            $result = $this->dishService->getAllDishes();
            respond(true, "Dish retrieved successfully", 200, $result);
        } catch (Exception $e) {
            respond(false, "Failed to retrieved all dishes" . $e->getMessage(), 400);
        }
    }

    public function updateDish(mixed $data): never
    {
        if (!isset($data['id'])) {
            respond(false, "Missing required fields id", 400);
        }
        try {
            $this->dishService->updateById(
                $data['id'],
                $data['name'] ?? null,
                $data['description'] ?? null,
                $data['price'] ?? null,
                $date['subcategory_id'] ?? null
            );
            respond(true, "Dish updated successfully");
        } catch (Exception $e) {
            respond(false, "Failed to update dish" . $e->getMessage(), 400);
        }
    }

    public function deleteDish(): never
    {
        if (!isset($_GET['dish_id'])) {
            respond(false, "Missing required field dish_id", 400);
        }
        try {
            $this->dishService->deleteDish($_GET['dish_id']);
            respond(true, "Dish deleted successfully");
        } catch (Exception $e) {
            respond(false, "Failed to delete dish" . $e->getMessage(), 400);
        }
    }
}