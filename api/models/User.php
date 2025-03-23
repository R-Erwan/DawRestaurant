<?php

namespace models;

require_once 'config/db.php';

class User {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function create($name, $email, $password, $role = 'user') {
        // Insertion utilisateur
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT)]);

        // Récupérer l'ID du nouvel utilisateur et du role
        $userId = $this->pdo->lastInsertId();
        $roleId = $this->getRoleIdByName($role);

        if (!$roleId) {
            $roleId = $this->getRoleIdByName('user'); // 'user' par défaut si rôle invalide
        }

        // Insertion dans la table user_roles
        $sql2 = "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)";
        $stmt2 = $this->pdo->prepare($sql2);
        $stmt2->execute([$userId, $roleId]);

        return $userId;
    }
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function getRoleIdByName($role){
        $sql = "SELECT id FROM roles WHERE name = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$role]);
        $role = $stmt->fetch();
        return $role ? $role['id'] : null;
    }

}
