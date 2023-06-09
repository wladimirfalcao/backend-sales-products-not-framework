<?php
namespace App\Models;

class ProductTypeModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM product_types');
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }

    public function add($tax, $name) {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO product_types (tax_percentage, name) VALUES (?, ?)');
            $stmt->execute([$tax, $name]);

            return $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
