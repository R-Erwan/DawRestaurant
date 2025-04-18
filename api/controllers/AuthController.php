<?php

namespace controllers;

use Exception\PasswordResetRateLimitException;
use services\AuthService;


class AuthController
{
    private AuthService $authService;

    public function __construct(\PDO $pdo)
    {
        $this->authService = new AuthService($pdo);
    }

    public function login(mixed $input): never
    {
        if (!isset($input['email']) || !isset($input['password'])) {
            respond(false, "Email and password are required", 400);
        }
        try {
            $result = $this->authService->login($input['email'], $input['password']);
            respond(true, "Logged in successfuly", 200, $result);
        } catch (\Exception $e) {
            respond(false, "Login failed : " . $e->getMessage(), 401);
        }
    }

    public function register(mixed $data): never
    {
        if (!isset($data['email']) || !isset($data['password']) || !isset($data['name'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $result = $this->authService->register($data['email'], $data['password'], $data['name']);
            respond(true, "User created successfully", 200, ["id" => $result]);
        } catch (\Exception $e) {
            respond(false, "Failed to register user : " . $e->getMessage(), 400);
        }
    }

    public function resetPasswordEmail(mixed $data): never
    {
        if (!isset($data['email'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $result = $this->authService->resetPasswordEmail($data['email']);
            if ($result) {
                respond(true, "Password reset links send successfully", 200);
            } else {
                respond(false, "Error sending reset links", 500);
            }
        } catch (PasswordResetRateLimitException $e) {
            respond(false, $e->getMessage(), $e->getCode());
        } catch (\Exception $e) {
            respond(false, "Error sending reset password links : " . $e->getMessage(), 400);
        }
    }

    public function resetPasswordToken(mixed $data): never
    {
        if (!isset($data['token']) || !isset($data['password'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $this->authService->resetPasswordToken($data['token'], $data['password']);
            respond(true, "Password reset successfully", 200);
        } catch (\Exception $e) {
            respond(false, "Error reset password : " . $e->getMessage(), 400);
        }
    }
}
