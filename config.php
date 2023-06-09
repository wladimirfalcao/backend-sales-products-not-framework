<?php

$host = 'localhost';
$dbname = 'salesproducts';
$username = 'postgres';
$password = '';
$schema = 'public';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname;options='--search_path=$schema'", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
}
