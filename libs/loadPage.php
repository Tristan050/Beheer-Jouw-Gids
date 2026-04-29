<?php

function loadPage(): void
{
    $config = require __DIR__ . '/pages.php';
    $routes = $config['routes'] ?? [];

    $page = $_GET['page'] ?? 'login';
    $page = basename($page);

    if (!isset($routes[$page])) {
        $page = '404';
    }

    $route = $routes[$page];
    if (isset($route['controller'], $route['method'])) {
        $controllerClass = (string)$route['controller'];
        $method = (string)$route['method'];

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                $controller->$method();
                return;
            }
        }
    }

    throw new HttpException(404, 'Pagina niet gevonden');
}
