<?php

return [
    'routes' => [
        'admin' => [
            'controller' => 'HomeController',
            'method' => 'admin',
        ],
        'login' => [
            'controller' => 'AuthController',
            'method' => 'index',
        ],
        'logout' => [
            'controller' => 'AuthController',
            'method' => 'logout',
        ],
        'home' => [
            'controller' => 'HomeController',
            'method' => 'index',
        ],
        '404' => [
            'controller' => 'ErrorController',
            'method' => 'notFound',
        ],
    ],
];
