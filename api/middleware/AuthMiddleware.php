<?php

namespace middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once 'config/config.php';

class AuthMiddleware {
    public static function verifyToken() {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? null;

        if (!$authHeader) {
            http_response_code(401);
            echo json_encode(["message" => "Authorization header missing"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $authHeader);

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["message" => "Invalid or expired token"]);
            exit;
        }
    }

    public static function verifyAdminAcces() {
        $authUser = self::verifyToken();
        if (!isset($authUser['roles']) || !in_array('admin', $authUser['roles'])) {
            http_response_code(403);
            echo json_encode(["message" => "Access denied. Admin role required."]);
            exit;
        }
        return $authUser;
    }

    public static function verifyAdminAccesWithoutExit() {
        $authUser = self::verifyToken();
        if (!isset($authUser['roles']) || !in_array('admin', $authUser['roles'])) {
           return false;
        }
        return $authUser;
    }

    public static function verifyUserAccess($requestedUserId) {
        $authUser = self::verifyToken();
        if($authUser['user_id'] !== $requestedUserId) {
            http_response_code(403);
            echo json_encode(["message" => "Unauthorized"]);
            exit;
        }
        return $authUser;

    }

}
