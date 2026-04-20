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
        '404' => [
            'controller' => 'ErrorController',
            'method' => 'notFound',
        ],
    ],
];
