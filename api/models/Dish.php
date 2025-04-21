<?php

namespace models;

require_once 'config/db.php';
class Dish {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($name, $description, $price, $subcategory) {
        $sql = "INSERT INTO dishes (name, description, price, subcategory_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $description, $price, $subcategory]);
        return $this->pdo->lastInsertId();
    }

    public function getAllDishesOrderedBySubcategory() {
        $sql = "SELECT d.name, d.description, d.price, s.name AS subcategory_name, c.name AS category_name FROM dishes d JOIN subcategories s on s.id = d.subcategory_id JOIN categories c on c.id = s.category_id ORDER BY d.subcategory_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateById(int $id, string $name = null, string $description = null, float $price = null, int $subcategory_id = null) {
        $fields = [];
        $params = [];

        if($name) {
            $fields[] = "name = ?";
            $params[] = $name;
        }
        if($description) {
            $fields[] = "description = ?";
            $params[] = $description;
        }
        if($price) {
            $fields[] = "price = ?";
            $params[] = $price;
        }
        if($subcategory_id) {
            $fields[] = "subcategory_id = ?";
            $params[] = $subcategory_id;
        }
        $params[] = $id;
        $sql = "UPDATE dishes SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteById(int $id) : bool {
        $sql = "DELETE FROM dishes WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}