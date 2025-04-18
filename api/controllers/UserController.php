<?php

namespace controllers;

use Exception;
use PDO;
use services\UserService;

class UserController
{
    private UserService $userService;

    public function __construct(PDO $pdo)
    {
        $this->userService = new UserService($pdo);
    }

    public function getUserInfoById(int $id): never
    {
        try {
            $result = $this->userService->getById($id);
            respond(true, "User found", 200, $result);
        } catch (Exception $e) {
            respond(false, "Can't retrieve user : " . $e->getMessage(), 400);
        }
    }

    public function updateUserById(int $requestedUserId, mixed $input): never
    {
        if (!isset($input['email']) || !isset($input['name'])) {
            respond(false, "Missing required fields", 400);
        }
        try {
            $this->userService->updateById(
                $requestedUserId,
                $input['name'],
                $input['email'],
                $input['first_name'] ?? null,
                $input['password'] ?? null,
                $input['phone_number'] ?? null
            );
            respond(true, "User found");
        } catch (Exception $e) {
            respond(false, "Can't update user : " . $e->getMessage(), 400);
        }
    }

    public function getUserRolesById(int $requestedUserId): never
    {
        try {
            $result = $this->userService->getRolesById($requestedUserId);
            respond(true, "User roles found", 200, $result);
        } catch (Exception $e) {
            respond(false, "Can't retrieved user : " . $e->getMessage(), 400);
        }
    }
}