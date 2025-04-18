<?php


namespace services;

use DateTime;
use Exception;
use Exception\PasswordResetRateLimitException;
use Firebase\JWT\JWT;
use models\User;
use Random\RandomException;

//require_once 'config/config.php';

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
     * @throws Exception
     */
    public function resetPasswordEmail($email): bool
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        $user = $this->user->findByEmail($email);
        if (!$user) {
            throw new Exception('Email does not exist');
        }

        $nowTs = (new DateTime())->getTimestamp();

        if (!empty($user['last_reset_request'])) {
            $lastTs = (new DateTime($user['last_reset_request']))->getTimestamp();
            if (($nowTs - $lastTs) < 300) { // moins de 5 minutes
                throw new Exception\PasswordResetRateLimitException();
            }
        }

        $token = $this->user->createTokenReset($email, $user['id']);
        return MailService::sendResetPasswordLink($email, $token);
    }

    /**
     * @throws Exception
     */
    public function resetPasswordToken($token, $password) {
            $tokenInfos =  $this->user->getTokenInfos($token);
            if(!$tokenInfos) {
                throw new Exception("Invalid token");
            }
            if(strtotime($tokenInfos['expires_at']) < time()) {
                throw new Exception("Token expired");
            }
            if(strlen($password) < 8){
                throw new Exception('Password must be at least 8 characters long');
            }

            return $this->user->resetPassword($tokenInfos['email'], $password);
    }

}
