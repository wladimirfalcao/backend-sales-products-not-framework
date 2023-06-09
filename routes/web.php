<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

use App\Controllers\AuthController;
use App\Controllers\ProductTypeController;
use App\Controllers\ProductController;
use App\Controllers\SaleController;

$authController = new AuthController($pdo);
$productTypeController = new ProductTypeController($pdo);
$productController = new ProductController($pdo);
$saleController = new SaleController($pdo);


return [
    'POST' => [
        '/login' => [$authController, 'login'],
        '/product' => [$productController, 'add'],
        '/product-type' => [$productTypeController, 'add'],
        '/sale' => [$saleController, 'add'],
    ],
    'GET' => [
        '/products' => [$productController, 'index'],
        '/product-types' => [$productTypeController, 'index'],
        '/sales' => [$saleController, 'index'],
        '/sale/(\d+)' => [$saleController, 'getSaleInvoice'],
    ],
];