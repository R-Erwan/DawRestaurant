<?php

namespace controllers;

use Exception;
use PDO;
use services\SubcategoryService;

class SubcategoryController
{
    private SubcategoryService $subcategoryService;

    public function __construct(PDO $pdo)
    {
        $this->subcategoryService = new SubcategoryService($pdo);
    }

    public function createSubcategory(mixed $data): never
    {
        if (!isset($data['name']) || !isset($data['category_id'])) {
            respond(false, "Missing required fields", 400);
        }

        try {
            $result = $this->subcategoryService->create(
                $data['name'],
                $data['category_id']
            );
            respond(true, "Subcategory created successfully", 200, ["id" => $result]);
        } catch (Exception $e) {
            respond(false, "Subcategory creation failed" . $e->getMessage(), 400);
        }
    }

    public function getAllSubcategories(): never
    {
        try {
            $result = $this->subcategoryService->getAllSubcategories();
            respond(true, "Subcategories retrieved", 200, $result);
        } catch (Exception $e) {
            respond(false, "Subcategories retrieval failed" . $e->getMessage(), 400);
        }
    }

    public function updateSubcategory(mixed $data): never
    {
        if (!isset($data['id'])) {
            respond(false, "Missing required field id", 400);
        }
        try {
            $this->subcategoryService->updateById(
                $data['id'],
                $data['name'] ?? null,
                $date['category_id'] ?? null
            );
            respond(true, "Subcategory updated successfully", 200);
        } catch (Exception $e) {
            respond(false, "Subcategory update failed" . $e->getMessage(), 400);
        }
    }

    public function deleteSubcategory(): never
    {
        if (!isset($_GET['subcategory_id'])) {
            respond(false, "Missing required field subcategory_id", 400);
        }
        try {
            $this->subcategoryService->deleteSubcategory($_GET['subcategory_id']);
            respond(true, "Subcategory deleted successfully");
        } catch (Exception $e) {
            respond(false, "Subcategory deletion failed" . $e->getMessage(), 400);
        }
    }
}