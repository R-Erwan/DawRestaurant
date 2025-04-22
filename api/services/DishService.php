<?php

namespace services;

use Exception;
use models\Dish;
use PDO;

class DishService
{
    private Dish $dish;

    public function __construct(PDO $pdo)
    {
        $this->dish = new Dish($pdo);
    }

    /**
     * @throws Exception
     */
    public function create(string $name, float $price, int $subcategory_id, ?string $description = null)
    {

        if (strlen($name) > 100) {
            throw new Exception("Dish name should be less than 100 characters");
        }

        if ($price <= 0) {
            throw new Exception("Price can't be negative");
        }

        return $this->dish->create($name, $description, $price, $subcategory_id);
    }

    public function getAllDishes()
    {
        return $this->dish->getAllDishesOrderedBySubcategory();
    }

    /**
     * @throws Exception
     */
    public function updateById(int $id, ?string $name = null, ?string $description = null, ?float $price = null, ?int $subcategory_id = null)
    {
        if (strlen($name) > 100) {
            throw new Exception("Dish name should be less than 100 characters");
        }
        if (!$price === null && $price <= 0) {
            throw new Exception("Price can't  be negative");
        }
        return $this->dish->updateById($id, $name, $description, $price, $subcategory_id);
    }

    /**
     * @throws Exception
     */
    public function deleteDish(int $id): void
    {
        $deleted = $this->dish->deleteById($id);
        if (!$deleted) {
            throw new Exception("Dish not found");
        }
    }
}