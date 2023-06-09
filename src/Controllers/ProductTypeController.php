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
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['tax_percentage'])) {
            return json_encode(['success' => false, 'message' => 'Taxa é obrigatório.']);
        }

        $tax = $data['tax_percentage'];
        $name = $data['name'];

        $type = new ProductTypeModel($this->pdo);
        $id = $type->add($tax, $name);

        if ($id) {
            $response = ['success' => true, 'message' => 'Produto cadastrado com sucesso!', 'typeId' => $id];
        } else {
            $response = ['success' => false, 'message' => 'Erro ao cadastrar o produto.'];
        }

        return json_encode($response);
    }
}
