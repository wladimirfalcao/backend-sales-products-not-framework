<?php

namespace App\Controllers;

use PDO;

class AuthController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->authenticateUser($email, $password);

        if ($user) {
            $response = ['success' => true, 'message' => 'Authentication successful', 'data' => $user];
        } else {
            $response = ['success' => false, 'message' => 'Invalid credentials'];
        }

        return $response;
    }

    private function authenticateUser($email, $password)
    {

        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashedPassword = $user['password'];

        if ($user && password_verify($password, $hashedPassword)) {
            unset($user['password']);
            unset($user['token']);
            $token  = $this->generateAccessToken($user['id']);
            return ['user' => $user, 'token' => $token];
        }

        return false;
    }


    private function generateAccessToken($userId)
    {
        try {
           $token = bin2hex(random_bytes(32));
        
            $stmt = $this->pdo->prepare("UPDATE users SET token = :token WHERE id = :id");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            return $token;
        } catch (\PDOException $e) {
            echo "Error updating record " . $e->getMessage();
        }
    }

    public function validateAccessToken($token, $userId) {
        
        $stmt = $this->pdo->prepare('SELECT token FROM users WHERE id = :id');
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userToken = $user['token'];

        if (isset($userToken) && $userToken === $token) {
            return true;
        }
    
        return false;
    }
}
