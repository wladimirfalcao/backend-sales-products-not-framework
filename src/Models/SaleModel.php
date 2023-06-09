<?php
namespace App\Models;

class SaleModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all(){
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM sales');
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function findSale($id){
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM sales WHERE id = ?');
            $stmt->execute([$id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function findSoldItemsSale($sale_id){
        try {
            $stmt = $this->pdo->prepare('SELECT si.quantity, p.name, p.price, pt.tax_percentage FROM sold_items as si
            INNER JOIN products p on si.product_id = p.id
            INNER JOIN product_types pt on p.type_id = pt.id
            WHERE sale_id = ?');
            $stmt->execute([$sale_id]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function addSale($products) {
        try {
            $this->pdo->beginTransaction();
            $date = date('Y-m-d H:i:s');
            $stmt = $this->pdo->prepare('INSERT INTO sales (total, created) VALUES (?, ?)');
            $stmt->execute([0, $date]);
            $saleId = $this->pdo->lastInsertId();

            $itemTotal = 0;
            foreach ($products as $product) {
                $productId = $product['id'];
                $quantity = $product['quantity'];

                $stmt = $this->pdo->prepare('SELECT price FROM products WHERE id = ?');
                $stmt->execute([$productId]);
                $price = $stmt->fetchColumn();

                $itemTotal += ($price * $quantity);
                $stmt = $this->pdo->prepare('INSERT INTO sold_items (sale_id, product_id, quantity) VALUES (?, ?, ?)');
                $stmt->execute([$saleId, $productId, $quantity]);
            }

            $stmt = $this->pdo->prepare('UPDATE sales SET total = ? WHERE id = ?');
            $stmt->execute([$itemTotal, $saleId]);

            $this->pdo->commit();
            return $saleId;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
