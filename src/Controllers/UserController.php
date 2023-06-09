<?php

namespace App\Controllers;

use App\Auth\Auth;
use App\Models\UserModel;

class UserController
{
    private $auth;
    private $userModel;

    public function __construct(Auth $auth, UserModel $userModel)
    {
        $this->auth      = $auth;
        $this->userModel = $userModel;
    }

    public function register()
    {

        $data = $_POST;
        if (empty($data)) {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);
        }
        $email = $data['email'];
        $password = $data['password'];

        if ($this->userModel->getUserByUsername($email)) {
            return ['success' => false, 'message' => 'User already exists!'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userModel->createUser($email, $hashedPassword);

        if ($userId) {
            $response = ['success' => true, 'message' => 'Successfully registered!'];
        } else {
            $response = ['success' => false, 'message' => 'Error while registering the user!'];
        }

        return $response;
    }
}
