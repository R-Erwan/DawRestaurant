<?php

namespace services;
use models\User;
require_once 'models/User.php';

class UserService{
    private $user;

    public function __construct($pdo) {
        $this->user = new User($pdo);
    }

    /**
     * @throws \Exception
     */
    public function getById($id){
        $user = $this->user->findById($id);
        if($user){
            unset($user['password']);
            return $user;
        }
        throw new \Exception("User not found");
    }
}