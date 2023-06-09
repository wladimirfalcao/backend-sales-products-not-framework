<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit();
}

$routes = require_once __DIR__ . '/../routes/web.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$matchedRoute = null;
$matchedParams = [];
$response = null;

$allowedRoutes = ['/login'];
$tokenValid = false;


if ($requestUri !== '/login' && isset($_SERVER['HTTP_AUTHORIZATION'])) {

    $token = $_SERVER['HTTP_AUTHORIZATION'] ?? 'Bearer ';
    $token = explode('Bearer ', $token)[1];
    $validateAccessToken = $authController->validateAccessToken($token, 4);

    if ($validateAccessToken) {
        $tokenValid = true;
    }
}

if (!in_array($requestUri, $allowedRoutes) && !$tokenValid) {
    http_response_code(401);
    $response = ['success' => false, 'message' => 'Acesso não autorizado, token expirado ou inválido!'];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


if (isset($routes[$requestMethod])) {
    $methodRoutes = $routes[$requestMethod];
    foreach ($methodRoutes as $route => $handler) {
        $pattern = '#^' . $route . '$#';
        if (preg_match($pattern, $requestUri, $matches)) {
            $matchedRoute = $route;
            $matchedParams = array_slice($matches, 1);
            break;
        }
    }
}

if ($matchedRoute !== null) {
    $handler = $routes[$requestMethod][$matchedRoute];
    $response = call_user_func_array($handler, $matchedParams);
    http_response_code(200);
    echo json_encode($response);
} else {
    http_response_code(404);
    $response = ['success' => false, 'message' => 'Página não encontrada'];
}

if (!isset($response['success'])) {
    http_response_code(501);
}

