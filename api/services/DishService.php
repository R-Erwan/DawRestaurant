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
}