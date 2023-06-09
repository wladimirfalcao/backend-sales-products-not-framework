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
            $response = ['success' => false, 'message' => 'Record not found!'];
        }
        return $response;
    }

    public function add()
    {
        $data = $_POST;
        if (empty($data)) {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);
        }

        if (!isset($data['products'])) {
            return ['success' => false, 'message' => 'Products are required!'];
        }

        $products = $data['products'];

        $saleModel = new SaleModel($this->pdo);
        $result = $saleModel->addSale($products);

        if ($result) {
            $response = ['success' => true, 'saleId' => $result, 'message' => 'Sale registered successfully!'];
        } else {
            $response = ['success' => false, 'message' => 'Error registering the sale!'];
        }

        return $response;
    }
}
