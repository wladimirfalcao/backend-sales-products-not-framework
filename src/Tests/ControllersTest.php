<?php

namespace App\Tests;

use App\Controllers\ProductTypeController;
use App\Controllers\ProductController;
use App\Controllers\SaleController;
use App\Models\ProductModel;
use App\Models\ProductTypeModel;
use PHPUnit\Framework\TestCase;


class ControllersTest extends TestCase
{
    protected $pdo;

    protected function setUp(): void
    {

        $host     = 'localhost';
        $dbname   = 'josew';
        $username = 'postgres';
        $password = '';
        $schema   = 'public';

        try {
            $this->pdo = new \PDO("pgsql:host=$host;dbname=$dbname;options='--search_path=$schema'", $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
        }

    }

    protected function tearDown(): void
    {
        $this->pdo->exec('TRUNCATE TABLE sales');
        $this->pdo->exec('TRUNCATE TABLE sold_items');
        $this->pdo->exec('TRUNCATE TABLE products, product_types CASCADE');
    }

    public function testAddProductType()
    {
        $productTypeController = new ProductTypeController($this->pdo);

        $postData = [
            'name' => 'Tipo de Produto',
            'tax_percentage' => 7.5,
        ];

        $_POST = $postData;
        $response = $productTypeController->add();

        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertEquals('Product type added successfully!', $response['message']);
        $this->assertArrayHasKey('typeId', $response);

        $productTypeModel = new ProductTypeModel($this->pdo);
        $productType = $productTypeModel->getTypeById($response['typeId']);

        $this->assertNotEmpty($productType);
        $this->assertEquals($postData['name'], $productType['name']);
        $this->assertEquals($postData['tax_percentage'], $productType['tax_percentage']);
    }


    public function testAddProduct()
    {

        $typeModel = new ProductTypeModel($this->pdo);
        $typeId = $typeModel->add(7.5, 'Test tax');

        $postData = [
            'name'   => 'Produto de Teste',
            'price'  => 10.99,
            'typeId' => $typeId
        ];

        $_POST    = $postData;

        $productController = new ProductController($this->pdo);
        $response = $productController->add();


        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertEquals('Product registered successfully!', $response['message']);
        $this->assertArrayHasKey('productId', $response);


        $productId    = $response['productId'];
        $productModel = new ProductModel($this->pdo);
        $product      = $productModel->getProductById($productId);

        $this->assertNotEmpty($product);
        $this->assertEquals($postData['name'], $product['name']);
        $this->assertEquals($postData['price'], $product['price']);
        $this->assertEquals($postData['typeId'], $product['type_id']);
    }

    public function testAddSale()
    {
        $products = [
            'products' => [
                ['id' => 1, 'quantity' => 2],
                ['id' => 2, 'quantity' => 3],
            ]
        ];

        $saleController = new SaleController($this->pdo);

        $_POST  = $products;
        $result = $saleController->add();

        $this->assertTrue($result['success']);

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM sales');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $this->assertEquals(1, $count);

        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM sold_items');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $this->assertEquals(count($products['products']), $count);
    }
}
