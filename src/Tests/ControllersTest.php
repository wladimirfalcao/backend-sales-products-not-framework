<?php

namespace App\Tests;

use App\Models\ProductModel;
use PHPUnit\Framework\TestCase;
use App\Controllers\ProductController;
use App\Controllers\SaleController;


class ControllersTest extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {
        // Configuração do banco de dados de teste

        $host     = 'localhost';
        $dbname   = 'products';
        $username = 'postgres';
        $password = '';
        $schema   = 'public';


        $pdo = new \PDO("pgsql:host=$host;dbname=$dbname;options='--search_path=$schema'", $username, $password);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        // Executa os scripts SQL de criação das tabelas necessárias
//        $this->pdo->exec(file_get_contents('create_tables.sql'));
    }

    protected function tearDown(): void
    {
        // Limpa as tabelas após cada teste
        $this->pdo->exec('TRUNCATE TABLE products');
        $this->pdo->exec('TRUNCATE TABLE sales');
    }

    public function testAddProduct()
    {
        $productController = new ProductController($this->pdo);

        $postData = [
            'name'   => 'Produto de Teste',
            'price'  => 10.99,
            'typeId' => 2
        ];

        $_POST    = $postData;
        $response = $productController->add();

        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertEquals('Produto cadastrado com sucesso!', $response['message']);
        $this->assertArrayHasKey('productId', $response);


        $productId    = $response['productId'];
        $productModel = new ProductModel($this->pdo);
        $product      = $productModel->getProductById($productId);

        $this->assertNotEmpty($product);
        $this->assertEquals($postData['name'], $product['name']);
        $this->assertEquals($postData['price'], $product['price']);
        $this->assertEquals($postData['typeId'], $product['typeId']);
    }

    public function testAddSale()
    {
        $saleController = new SaleController($this->pdo);
        $response = $saleController->add();

        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
    }
}
