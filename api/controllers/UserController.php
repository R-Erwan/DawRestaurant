<?php

namespace controllers;

use services\UserService;
require_once 'services/UserService.php';

class UserController{
    private $userService;
    public function __construct(\PDO $pdo){
        $this->userService = new UserService($pdo);
    }

    public function getUserInfoById($id){
        try {
            $result = $this->userService->getById($id);
            http_response_code(200);
            echo json_encode([
                'message' => 'User found',
                'user' => $result
            ]);
        } catch (\Exception $e){
            http_response_code(404);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
}