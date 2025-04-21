<?php

namespace services;
use models\Dish;
require_once 'models/Dish.php';

class DishService
{
    private Dish $dish;

    public function __construct($pdo)
    {
        $this->dish = new Dish($pdo);
    }

    /**
     * @throws \Exception
     */
    public function create($name, $price, $subcategory_id, $description = null){

        if($name === null || strlen($name) > 100) {
            throw new \Exception("Invalid coherence Name is required");
        }
        if($price === null || $price <= 0) {
            throw new \Exception("Invalid coherence price is required");
        }
        if($subcategory_id === null) {
            throw new \Exception("Invalid coherence subcategory is required");
        }
        return $this->dish->create($name, $description, $price, $subcategory_id);
    }

    public function getAllDishes() {
        return $this->dish->getAllDishesOrderedBySubcategory();
    }

    /**
     * @throws \Exception
     */
    public function getBySubcategoryId($subcategoryId){
        $dish = $this->dish->findBySubcategory($subcategoryId);
        if($dish){
            return $dish;
        }
        throw new \Exception("Dish not found");
    }

    public function deleteDish($id): void
    {
        $deleted = $this->dish->deleteById($id);
        if(!$deleted){
            throw new \Exception("Dish not found");
        }
    }
}