<?php

namespace services;
use models\User;
require_once 'models/User.php';

class UserService{
    private User $user;

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

    /**
     * @throws \Exception
     */
    public function updateById($id, $name, $email, $firstName = null, $password = null, $phone = null, $role = 'user'){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception('Invalid email format');
        }

        if($password && strlen($password) < 8){
            throw new \Exception('Password must be at least 6 characters long');
        }

        $regex = "/^\+?\d{1,4}?[-.\s]?\(?\d{1,3}?\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}[-.\s]?\d{1,9}$/";
        if($phone && !filter_var($phone, FILTER_VALIDATE_REGEXP,array("options" => array("regexp" => $regex)))){
            throw new \Exception('Invalid phone number format');
        }

        if($this->user->emailExistsExceptCurrentUser($email,$id)){
            throw new \Exception('Email already in use');
        }

        return $this->user->updateById($id, $name, $email, $firstName, $password, $phone, $role);
    }
}