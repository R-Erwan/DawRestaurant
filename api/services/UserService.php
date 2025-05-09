<?php

namespace services;

use Exception;
use models\User;
use PDO;

class UserService
{
    private User $user;

    public function __construct(PDO $pdo)
    {
        $this->user = new User($pdo);
    }

    /**
     * @throws Exception
     */
    public function getById(int $id)
    {
        $user = $this->user->findById($id);
        if ($user) {
            unset($user['password']);
            return $user;
        }
        throw new Exception("User not found");
    }

    /**
     * @throws Exception
     */
    public function updateById(int $id, string $name, string $email, string $firstName = null, string $password = null, string $phone = null): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        if ($password && strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }

        $regex = "/^\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/";
        if ($phone && !filter_var($phone, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => $regex)))) {
            throw new Exception('Invalid phone number format');
        }

        if ($this->user->emailExistsExceptCurrentUser($email, $id)) {
            throw new Exception('Email already in use');
        }

        return $this->user->updateById($id, $name, $email, $firstName, $password, $phone);
    }

    /**
     * @throws Exception
     */
    public function getRolesById(mixed $requestedUserId): array
    {
        $roles = $this->user->findRolesById($requestedUserId);
        if ($roles) {
            return $roles;
        }
        throw new Exception("User not found");
    }
}