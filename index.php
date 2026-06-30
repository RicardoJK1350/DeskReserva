<?php
require_once __DIR__ . '/config.php';

$rota = $_GET['route'] ?? 'auth_login';

$partes = explode('_', $rota);

$controllerName = ucfirst($partes[0]) . 'Controller';

$method = $partes[1] ?? 'index';

if (class_exists($controllerName)) {
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {
        $controller->$method();
    } else {
        (new PageController())->index();
    }
} else {
    (new PageController())->index();
}
