<?php

namespace models;

require_once 'config/db.php';
class Subcategory {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($name, $category) {
        $sql = "INSERT INTO subcategories (name, category_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $category]);
        return $this->pdo->lastInsertId();
    }

    public function getAllSubcategories() {
        $sql = "SELECT s.name AS subcategory_name, c.name AS category_name FROM subcategories s JOIN categories c on c.id = s.category_id ORDER BY s.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateById(int $id, string $name = null, string $category_id = null) {
        $fields = [];
        $params = [];

        if($name) {
            $fields[] = "name = ?";
            $params[] = $name;
        }
        if($category_id) {
            $fields[] = "category_id = ?";
            $params[] = $category_id;
        }
        $params[] = $id;
        $sql = "UPDATE subcategories SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteById(int $id) : bool {
        $sql = "DELETE FROM subcategories WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}