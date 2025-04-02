<?php

namespace models;

class Announce {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create(int $type, int $ordering, string $title = null, string $description = null, string $image_url = null) {
        $sql = "INSERT INTO announces (type, ordering, title, description, image_url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$type, $ordering, $title, $description, $image_url]);
        return $this->pdo->lastInsertId();
    }

    public function findById(int $id) {
        $sql = "SELECT * FROM announces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAll(){
        $sql = "SELECT * FROM announces";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateById(int $id, int $ordering, string $title = null, string $description = null, string $image_url = null) {
        $fields = [];
        $params = [];

        $fields[] = "ordering = ?";
        $params[] = $ordering;

        if($title) {
            $fields[] = "title = ?";
            $params[] = $title;
        }
        if($description) {
            $fields[] = "description = ?";
            $params[] = $description;
        }
        if($image_url) {
            $fields[] = "image_url = ?";
            $params[] = $image_url;
        }
        $sql = "UPDATE announces SET " . implode(", ", $fields) . " WHERE id = ?";
        $params[] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function deleteById(int $id) {
        $sql = "DELETE FROM announces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

}