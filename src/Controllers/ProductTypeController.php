<?php
namespace App\Controllers;

use App\Models\ProductTypeModel;

class ProductTypeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index(){
        $type = new ProductTypeModel($this->pdo);
        return  ['success' => false, 'data' => $type->getAll()];
    }

    public function add() {

        $data = $_POST;
        if (empty($data)) {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);
        }

        if (!isset($data['tax_percentage'])) {
            return ['success' => false, 'message' => 'Tax percentage is required!'];
        }

        $tax = $data['tax_percentage'];
        $name = $data['name'];

        $type = new ProductTypeModel($this->pdo);
        $id = $type->add($tax, $name);

        if ($id) {
            $response = ['success' => true, 'message' => 'Product type added successfully!', 'typeId' => $id];
        } else {
            $response = ['success' => false, 'message' => 'Error adding the product type.'];
        }

        return $response;
    }
}
