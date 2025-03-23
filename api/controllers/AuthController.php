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
}
