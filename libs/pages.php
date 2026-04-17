<?php

return [
    'routes' => [
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
