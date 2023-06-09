<?php

namespace App\Models;

class ProductModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        try {
            $stmt = $this->pdo->prepare('SELECT products.*, tax_percentage  FROM products 
                                         INNER JOIN product_types as pt ON pt.id = products.type_id
                                         ORDER BY products.name');
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }

    }

    public function addProduct($name, $price, $typeId)
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO products (name, price, type_id) VALUES (?, ?, ?)');
            $stmt->execute([$name, $price, $typeId]);
            return $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getProductById($productId)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :id');
            $stmt->bindParam(':id', $productId);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getTaxPercentage($typeId)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT tax_percentage FROM product_types WHERE id = :id');
            $stmt->bindParam(':id', $typeId);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $result['tax_percentage'];
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
