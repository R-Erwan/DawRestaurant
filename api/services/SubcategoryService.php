<?php

namespace services;
use models\Subcategory;
require_once 'models/Subcategory.php';

class SubcategoryService
{
    private Subcategory $subcategory;

    public function __construct($pdo)
    {
        $this->subcategory = new Subcategory($pdo);
    }

    /**
     * @throws \Exception
     */
    public function create($name, $category_id){

        if(strlen($name) > 100) {
            throw new \Exception("Invalid coherence Name is required");
        }
        return $this->subcategory->create($name, $category_id);
    }

    public function getAllSubcategories() {
        return $this->subcategory->getAllSubcategories();
    }

    /**
     * @throws \Exception
     */
    public function updateById(int $id, string $name = null, int $category_id = null){
        if(!$name === null && strlen($name) > 100) {
            throw new \Exception("Invalid coherence A smaller name is required");
        }
        return $this->subcategory->updateById($id, $name, $category_id);
    }

    /**
     * @throws \Exception
     */
    public function deleteSubcategory($id): void
    {
        $deleted = $this->subcategory->deleteById($id);
        if(!$deleted){
            throw new \Exception("Dish not found");
        }
    }
}