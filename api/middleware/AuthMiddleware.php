<?php

namespace middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

//require_once 'config/config.php';

class AuthMiddleware
{
    public static function verifyToken()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? null;

        if (!$authHeader) {
            respond(false,"Missing authorization header",401);
        }

        $token = str_replace("Bearer ", "", $authHeader);

        try {
            $decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
            return (array)$decoded;
        } catch (\Exception $e) {
            respond(false,"Invalid or expired token",401);
        }
    }

    public static function verifyAdminAcces(): ?array
    {
        $authUser = self::verifyToken();
        if (!isset($authUser['roles']) || !in_array('admin', $authUser['roles'])) {
            respond(false,"Access denied admin role required",403);
        }
        return $authUser;
    }

    public static function verifyAdminAccesWithoutExit(): false|array
    {
        $authUser = self::verifyToken();
        if (!isset($authUser['roles']) || !in_array('admin', $authUser['roles'])) {
           return false;
        }
        return $authUser;
    }

    public static function verifyUserAccess($requestedUserId): ?array
    {
        $authUser = self::verifyToken();
        if($authUser['user_id'] !== $requestedUserId) {
            respond(false,"Unauthorized",401);
        }
        return $authUser;

    }

}
