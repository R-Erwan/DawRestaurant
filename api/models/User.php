<?php

namespace models;

use PDO;
use Random\RandomException;

require_once 'config/db.php';

class User
{
    private PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $name, string $email, int $password, string $role = 'user'): false|string
    {
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

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function emailExistsExceptCurrentUser(string $email, int $id): bool
    {
        // Requête SQL pour vérifier si un autre utilisateur avec cet email existe
        $sql = "SELECT COUNT(*) FROM users WHERE email = ? AND id != ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $id]);

        // Si le compte est supérieur à 0, cela signifie qu'il y a déjà un utilisateur avec cet email
        return $stmt->fetchColumn() > 0;
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function getRoleIdByName(string $role)
    {
        $sql = "SELECT id FROM roles WHERE name = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$role]);
        $role = $stmt->fetch();
        return $role ? $role['id'] : null;
    }

    public function updateById(int $id, string $name, string $email, string $firstName = null, string $password = null, string $phone = null): bool
    {
        // Préparation des parties de la requête
        $fields = [];
        $params = [];

        // Obligatoire : mise à jour du nom et de l'email
        $fields[] = "name = ?";
        $fields[] = "email = ?";
        $params[] = $name;
        $params[] = $email;

        // Optionnel : mise à jour du prénom si fourni
        if ($firstName !== null) {
            $fields[] = "first_name = ?";
            $params[] = $firstName;
        }

        // Optionnel : mise à jour du mot de passe si fourni
        if ($password !== null) {
            $fields[] = "password = ?";
            $params[] = password_hash($password, PASSWORD_BCRYPT); // On hache le mot de passe
        }

        // Optionnel : mise à jour du téléphone si fourni
        if ($phone !== null) {
            $fields[] = "phone_number = ?";
            $params[] = $phone; // On hache le mot de passe
        }


        // Assemblage de la requête
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $id;  // Ajout de l'ID de l'utilisateur pour la condition WHERE

        // Exécution de la requête préparée
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function findRolesById(int $requestedUserId): ?array
    {
        $sql = "SELECT r.name FROM roles r 
                JOIN user_roles ur ON r.id = ur.role_id
                WHERE ur.user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$requestedUserId]);
        return $stmt->fetchAll();
    }

    /**
     * @throws RandomException
     */
    public function createTokenReset(string $email, int $user_id): string
    {
        $sql = "UPDATE users SET last_reset_request = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);
        return $token;
    }

    public function getTokenInfos(string $token)
    {
        $sql = "SELECT email, expires_at FROM password_resets WHERE token = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function resetPassword(string $email, string $password): bool
    {
        $sql = "UPDATE users set password = ? WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([password_hash($password, PASSWORD_BCRYPT), $email]);

        //Supprime tous les tokens de reset de l'utilisateur
        $sql2 = "DELETE FROM password_resets WHERE email = ?";
        $stmt2 = $this->pdo->prepare($sql2);
        return $stmt2->execute([$email]);
    }


}
