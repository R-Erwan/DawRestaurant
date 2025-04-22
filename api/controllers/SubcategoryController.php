<?php

namespace controllers;

use models\Subcategory;
use services\SubcategoryService;
require_once 'services/SubcategoryService.php';

class SubcategoryController
{
    private SubcategoryService $subcategoryService;

    public function __construct(\PDO $pdo)
    {
        $this->subcategoryService = new SubcategoryService($pdo);
    }

    public function createSubcategory($data): void {
        if(!isset($data['name'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Name is required']);
            exit;
        }

        if(!isset($data['category_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Category is required']);
            exit;
        }

        try {
            $result = $this->subcategoryService->create(
                $data['name'],
                $data['category_id']
            );
            http_response_code(200);
            echo json_encode(["message" => "Subcategory created successfully", "result" => $result]);
        } catch(\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function getAllSubcategories(): void {
        try {
            $result = $this->subcategoryService->getAllSubcategories();
            http_response_code(200);
            echo json_encode(["message" => "Subcategories retrieved successfully", "result" => $result]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function updateSubcategory($data): void {
        if(!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Id is required']);
            exit;
        }
        try {
            $result = $this->subcategoryService->updateById(
                $data['id'],
                $data['name'] ?? null,
                $date['category_id'] ?? null
            );
            http_response_code(200);
            echo json_encode(["message" => "Subcategory updated successfully"]);
        } catch (\Exception $e){
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function deleteSubcategory(): void {
        if(!isset($_GET['subcategory_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'subcategory_id is required']);
        }
        try {
            $this->subcategoryService->deleteSubcategory($_GET['subcategory_id']);
            http_response_code(200);
            echo json_encode(['message' => 'Subcategory deleted successfully']);
        } catch(\Exception $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}