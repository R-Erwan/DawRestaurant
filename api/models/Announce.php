<?php

namespace models;

class Announce {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create(int $type, string $title = null, string $description = null, string $image_url = null) {
        $sql = "INSERT INTO announces (type, position, title, description, image_url) VALUES (?, (SELECT COALESCE(MAX(position), 0) + 1 FROM announces), ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$type, $title, $description, $image_url]);
        return $this->pdo->lastInsertId();
    }

    public function getTypeById(int $id) {
        $sql = "SELECT type FROM announces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result['type'];
    }

    public function findById(int $id) {
        $sql = "SELECT * FROM announces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAll(){
        $sql = "SELECT * FROM announces ORDER BY position ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @throws \Exception
     */
    public function updatePosition(int $id, int $newPosition) {
        // Récupérer la position actuelle
        $sql = "SELECT position FROM announces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $currentPosition = $stmt->fetchColumn();

        if ($currentPosition === false) {
            throw new \Exception("Announce not found.");
        }

        if ($currentPosition == $newPosition) {
            return; // Pas de changement
        }

        // Décaler les autres annonces
        if ($currentPosition < $newPosition) {
            // On décale vers le haut
            $sql = "UPDATE announces SET position = position - 1 WHERE position > ? AND position <= ?";
            $this->pdo->prepare($sql)->execute([$currentPosition, $newPosition]);
        } else {
            // On décale vers le bas
            $sql = "UPDATE announces SET position = position + 1 WHERE position >= ? AND position < ?";
            $this->pdo->prepare($sql)->execute([$newPosition, $currentPosition]);
        }

        // Mettre à jour la position de l'annonce cible
        $sql = "UPDATE announces SET position = ? WHERE id = ?";
        return $this->pdo->prepare($sql)->execute([$newPosition, $id]);
    }

    public function reorderAll() {
        $sql = "WITH reordered AS (
                SELECT id, ROW_NUMBER() OVER (ORDER BY position ASC) AS new_position
                FROM announces
            )
            UPDATE announces a
            SET position = r.new_position
            FROM reordered r
            WHERE a.id = r.id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }


    public function updateById(int $id, string $title = null, string $description = null, string $image_url = null) {
        $fields = [];
        $params = [];

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

    public function deleteById(int $id) : bool {
        $sql = "DELETE FROM announces WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

}