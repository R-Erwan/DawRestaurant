<?php


namespace services;

use models\User;
use Firebase\JWT\JWT;
require_once 'models/User.php';
require_once 'config/config.php';

class AuthService {
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    public function login($email, $password) {
        $user = $this->user->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $payload = [
                "user_id" => $user['id'],
                "email" => $user['email'],
                "exp" => time() + 3600 // Expiration dans 1h
            ];

            $token = JWT::encode($payload, JWT_SECRET, 'HS256');
            return ["token" => $token];
        }
        return null; // Authentication failed
    }
}
