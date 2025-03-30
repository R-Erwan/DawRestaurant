<?php

namespace middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once 'config/config.php';

class AuthMiddleware
{
    public static function verifyToken()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? null;

        if (!$authHeader) {
            http_response_code(401);
            echo json_encode(["message" => "Authorization header missing"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $authHeader);

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            return (array)$decoded;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid or expired token"]);
            exit;
        }
    }
}
