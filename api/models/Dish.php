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
        $sql = "SELECT d.id, d.name, d.description, d.price, s.name AS subcategory_name, c.name AS category_name 
                FROM dishes d 
                RIGHT JOIN subcategories s on s.id = d.subcategory_id 
                JOIN categories c on c.id = s.category_id 
                ORDER BY d.subcategory_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySubcategory($subcategory) {
        $sql = "SELECT * FROM dishes WHERE subcategory_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$subcategory]);
        return $stmt->fetch();
    }
}