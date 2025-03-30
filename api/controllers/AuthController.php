<?php

namespace controllers;

use services\AuthService;

require_once 'services/AuthService.php';

class AuthController
{
    private AuthService $authService;

    public function __construct($pdo)
    {
        $this->authService = new AuthService($pdo);
    }

    public function login($input): void
    {
        if (!isset($input['email']) || !isset($input['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Email and password are required']);
            exit;
        }
        try {
            $result = $this->authService->login($input['email'], $input['password']);
            http_response_code(200);
            echo json_encode([
                'message' => 'Logged in successfully',
                'token' => $result['token']
            ]);
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function register($data): void
    {
        if (!isset($data['email']) || !isset($data['password']) || !isset($data['name'])) {
            http_response_code(400);
            echo json_encode(['message' => "Missing required fields"]);
            return;
        }
        try {
            $result = $this->authService->register($data['email'], $data['password'], $data['name']);
            echo json_encode(['message' => 'User created successfully']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}
