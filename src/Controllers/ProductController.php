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
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data)
            $data = $_POST;

        if (!isset($data['name']) || !isset($data['price']) || !isset($data['typeId'])) {
            return ['success' => false, 'message' => 'Nome, preço e tipo são campos obrigatórios.'];
        }

        $name = $data['name'];
        $price = $data['price'];
        $typeId = $data['typeId'];

        $productModel = new ProductModel($this->pdo);
        $productId = $productModel->addProduct($name, $price, $typeId);

        if ($productId) {
            $response = ['success' => true, 'message' => 'Produto cadastrado com sucesso!', 'productId' => $productId];
        } else {
            $response = ['success' => false, 'message' => 'Erro ao cadastrar o produto.'];
        }

        return $response;
    }
}
