<?php


namespace services;

use models\User;
use Firebase\JWT\JWT;
require_once 'models/User.php';
require_once 'config/config.php';

class AuthService {
    private User $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    /**
     * @throws \Exception
     */
    public function login($email, $password): array
    {
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

        throw new \Exception("Invalid credentials");
    }

    /**
     * @throws \Exception
     */
    public function register($email, $password, $name){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception('Invalid email format');
        }

        if(strlen($password) < 8){
            throw new \Exception('Password must be at least 8 characters long');
        }

        if( $this->user->findByEmail($email)){
            throw new \Exception('Email already in use');
        }

        return $this->user->create($name, $email, $password);
    }
}
