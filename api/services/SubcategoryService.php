<?php

namespace services;
use Exception;
use models\Subcategory;
use PDO;

class SubcategoryService
{
    private Subcategory $subcategory;

    public function __construct(PDO $pdo)
    {
        $this->subcategory = new Subcategory($pdo);
    }

    /**
     * @throws Exception
     */
    public function create(string $name, int $category_id){

        if(strlen($name) > 100) {
            throw new Exception("Category name should be less than 100 characters");
        }
        return $this->subcategory->create($name, $category_id);
    }

    public function getAllSubcategories() {
        return $this->subcategory->getAllSubcategories();
    }

    /**
     * @throws Exception
     */
    public function updateById(int $id, ?string $name = null, ?int $category_id = null){
        if(strlen($name) > 100) {
            throw new Exception("Category name should be less than 100 characters");
        }
        return $this->subcategory->updateById($id, $name, $category_id);
    }

    /**
     * @throws Exception
     */
    public function deleteSubcategory(int $id): void
    {
        $deleted = $this->subcategory->deleteById($id);
        if(!$deleted){
            throw new Exception("Dish not found");
        }
    }
}