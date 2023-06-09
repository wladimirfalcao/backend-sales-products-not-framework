<?php
namespace App\Controllers;

use App\Models\ProductModel;

class ProductController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index(){
        $productModel = new ProductModel($this->pdo);
        return  ['success' => true, 'data' => $productModel->getAll()];
    }

    public function add() {
        $data = $_POST;
        if (empty($data)) {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);
        }

        if (!isset($data['name']) || !isset($data['price']) || !isset($data['typeId'])) {
            return ['success' => false, 'message' => 'Name, price, and type are required fields!'];
        }

        $name = $data['name'];
        $price = $data['price'];
        $typeId = $data['typeId'];

        $productModel = new ProductModel($this->pdo);
        $productId = $productModel->addProduct($name, $price, $typeId);

        if ($productId) {
            $response = ['success' => true, 'message' => 'Product registered successfully!', 'productId' => $productId];
        } else {
            $response = ['success' => false, 'message' => 'Error while registering the product!'];
        }

        return $response;
    }
}
