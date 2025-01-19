<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once './vendor/autoload.php';
require_once 'routes.php';

use APP\Helpers\RouterHelper;
use APP\Router;

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// instancia Router, passando array $routes, que vem do arquivo: routes.php
$router = new Router($routes);

$resolvedURL = $router->resolveURL($requestUri, $requestMethod);
if(!$resolvedURL) {
    var_dump('Rota não encontrada!');
    exit;
}

$routeInfo = $routes[$requestMethod][$resolvedURL];
$controllerDependencies = [];

foreach($routeInfo['dependencies'] as $dependency) {
    $controllerDependencies[] = $router->resolveDependencies($dependency);
}

// Cria o controlador e chama a action
$controller = new $routeInfo['controller'](...$controllerDependencies);
$action = $routeInfo['action'];

$params = [];
if(!empty($_POST)) {
    $params = array_values($_POST);
}

// adiciona parametros dinamicos no comeco do array
if($router->getDynamicParams()){
    array_unshift($params, ...$router->getDynamicParams());
}

// chama action
if(!empty($params)){
    $controller->$action(...$params);
}else{
    $controller->$action();
}



?>

