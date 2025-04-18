<?php

namespace controllers;

use services\UserService;

class UserController
{
    private UserService $userService;

    public function __construct(\PDO $pdo)
    {
        $this->userService = new UserService($pdo);
    }

    public function getUserInfoById($id): void
    {
        try {
            $result = $this->userService->getById($id);
            http_response_code(200);
            echo json_encode([
                'message' => 'User found',
                'user' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function updateUserById($requestedUserId, $input): void
    {
        if (!isset($input['email']) || !isset($input['name'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Email and name are required']);
            exit;
        }
        try {
            $result = $this->userService->updateById(
                $requestedUserId,
                $input['name'],
                $input['email'],
                $input['first_name'] ?? null,
                $input['password'] ?? null,
                $input['phone_number'] ?? null
            );
            echo json_encode(["message" => "User updated successfully"]);
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }

    public function getUserRolesById(mixed $requestedUserId) : void{
        try {
            $result = $this->userService->getRolesById($requestedUserId);
            http_response_code(200);
            echo json_encode([
                "message" => "User roles found",
                "roles" => $result
            ]);
        } catch (\Exception $e){
            http_response_code(404);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
}