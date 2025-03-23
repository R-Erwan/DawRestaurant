<?php

namespace middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once 'config/config.php';

class AuthMiddleware {
    public static function verifyToken() {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["message" => "Authorization token missing"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid or expired token"]);
            exit;
        }
    }
}
