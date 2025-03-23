<?php

namespace controllers;

use services\AuthService;
require_once 'services/AuthService.php';

class AuthController {
    private $authService;

    public function __construct($pdo) {
        $this->authService = new AuthService($pdo);
    }

    public function login($email, $password) {
        $authData = $this->authService->login($email, $password);
        if ($authData) {
            echo json_encode([
                'message' => 'Logged in successfully',
                'token' => $authData['token']
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => "Invalid credentials"]);
        }
    }

    public function register($data) {
        if(!isset($data['email']) || !isset($data['password']) || !isset($data['name'])) {
            http_response_code(400);
            echo json_encode(['message' => "Missing required fields"]);
            return;
        }

        try {
            $result = $this->authService->register($data['email'], $data['password'], $data['name']);
            echo json_encode(['message' => 'User created successfully']);
        } catch (\Exception $e){
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}
