<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once './vendor/autoload.php';
require_once 'routes.php';

use APP\Helpers\RouterHelper;

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (isset($routes[$requestMethod][$requestUri])) {
    $route = $routes[$requestMethod][$requestUri];
    $controllerDependencies = [];
    foreach($route['dependencies'] as $dependency) {
        $controllerDependencies[] = resolveDependencies($dependency);
    }

    // Cria o controlador e chama a ação
    $controller = new $route['controller'](...$controllerDependencies);
    $action = $route['action'];
    $params = [];
    if(!empty($_POST)) {
        $params = array_values($_POST);
    }
    $controller->$action(...$params);
} else {
    http_response_code(404);
    echo "Rota não encontrada.";
}

?>

