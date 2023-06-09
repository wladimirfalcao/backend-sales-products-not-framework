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

        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
        $password = $data['password'];

        if ($this->userModel->getUserByUsername($username)) {
            return ['success' => false, 'message' => 'Usuario já exite!'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userModel->createUser($username, $hashedPassword);

        if ($userId) {
            $response = ['success' => true, 'message' => 'Registrada com sucesso!'];
        } else {
            $response = ['success' => false, 'message' => 'Erro ao registrar a usuario.'];
        }

        return $response;
    }
}
