<?php


namespace services;

use DateTime;
use Exception;
use Exception\PasswordResetRateLimitException;
use Firebase\JWT\JWT;
use models\User;
use Random\RandomException;

require_once 'models/User.php';
require_once 'config/config.php';
require_once 'services/MailService.php';
require_once 'Exception/PasswordResetRateLimitException.php';

class AuthService
{
    private User $user;

    public function __construct($pdo)
    {
        $this->user = new User($pdo);
    }

    /**
     * @throws Exception
     */
    public function login($email, $password): array {
        $user = $this->user->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            $roles = array_map(fn($role) => $role['name'], $this->user->findRolesById($user['id']));
            $payload = [
                "user_id" => $user['id'],
                "email" => $user['email'],
                "roles" => $roles,
                "exp" => time() + 3600 // Expiration dans 1h
            ];

            $token = JWT::encode($payload, JWT_SECRET, 'HS256');
            return ["token" => $token];
        }

        throw new Exception("Invalid credentials");
    }

    /**
     * @throws Exception
     */
    public function register($email, $password, $name)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        if (strlen($password) < 8) {
            throw new Exception('Password must be at least 8 characters long');
        }

        if ($this->user->findByEmail($email)) {
            throw new Exception('Email already in use');
        }

        return $this->user->create($name, $email, $password);
    }

    /**
     * @throws RandomException
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \DateMalformedStringException
     * @throws PasswordResetRateLimitException
     */
    public function resetPasswordEmail($email): bool
    {
        $user = $this->user->findByEmail($email);
        if(!$user){
            return false;
        }

        $nowTs = (new DateTime())->getTimestamp();
        $lastTs = (new DateTime($user['last_reset_request']))->getTimestamp();

        if (($nowTs - $lastTs) < 300) { // Une demande toute les 5min
            throw new Exception\PasswordResetRateLimitException();
        }


        $token = $this->user->createTokenReset($email,$user['id']);
        return MailService::sendResetPasswordLink($email, $token);
    }
}
