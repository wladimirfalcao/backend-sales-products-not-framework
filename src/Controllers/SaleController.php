<?php

namespace App\Controllers;

use App\Models\SaleModel;

class SaleController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $saleModel = new SaleModel($this->pdo);
        $sale = $saleModel->all();
        return ['success' => true, 'data' => $sale];
    }

    public function getSaleInvoice($id)
    {
        $saleModel = new SaleModel($this->pdo);
        $sale = $saleModel->findSale($id);
        $soldItems = $saleModel->findSoldItemsSale($id);

        if ($sale) {
            $response = ['success' => true, 'sale' => $sale, 'sold_items' => $soldItems];
        } else {
            $response = ['success' => false, 'message' => 'Registro não encontrado!'];
        }
        return $response;
    }

    public function add()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $products = $data['products'];

        $saleModel = new SaleModel($this->pdo);
        $result = $saleModel->addSale($products);

        if ($result) {
            $response = ['success' => true, 'saleId' => $result, 'message' => 'Venda registrada com sucesso!'];
        } else {
            $response = ['success' => false, 'message' => 'Erro ao registrar a venda.'];
        }

        return $response;
    }
}
