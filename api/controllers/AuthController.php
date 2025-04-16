<?php

namespace controllers;

use Exception\PasswordResetRateLimitException;
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
            exit;
        }
        try {
            $result = $this->authService->register($data['email'], $data['password'], $data['name']);
            http_response_code(200);
            echo json_encode(['message' => 'User created successfully']);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function resetPasswordEmail($data): void{
        if(!isset($data['email'])) {
            http_response_code(400);
            echo json_encode(['message' => "Missing required fields"]);
            exit;
        }
        try {
            $result = $this->authService->resetPasswordEmail($data['email']);
            if($result){
                http_response_code(200);
                echo json_encode(['message' => 'Password reset links send successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Error sending reset links']);
            }

        } catch (PasswordResetRateLimitException $e){
            http_response_code($e->getCode());
            echo json_encode(['message' => $e->getMessage()]);

        } catch (\Exception $e){
            http_response_code(400);
            echo json_encode(['message' => "Error sending reset password links". $e->getMessage()]);
        }
    }

    public function resetPasswordToken($data): void{
        if(!isset($data['token']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => "Missing required fields"]);
            exit;
        }
        try {
            $result = $this->authService->resetPasswordToken($data['token'], $data['password']);
            http_response_code(200);
            echo json_encode(["message" => "Password reset successfully"]);
        } catch (\Exception $e){
            http_response_code(400);
            echo json_encode(["message" => "Error reset password : ". $e->getMessage()]);
        }
    }
}
