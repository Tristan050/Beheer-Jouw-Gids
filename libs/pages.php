<?php

return [
    'routes' => [
        'admin' => [
            'controller' => 'HomeController',
            'method' => 'index',
        ],
        'login' => [
            'controller' => 'AuthController',
            'method' => 'index',
        ],
        'logout' => [
            'controller' => 'AuthController',
            'method' => 'logout',
        ],
        'leefgebieden' => [
            'controller' => 'LeefgebiedenController',
            'method' => 'index',
        ],
        'leefgebied-edit' => [
            'controller' => 'LeefgebiedenController',
            'method' => 'edit',
        ],
        'leefgebied-save' => [
            'controller' => 'LeefgebiedenController',
            'method' => 'save',
        ],
        'functies' => [
            'controller' => 'FunctiesController',
            'method' => 'index',
        ],
        'functie-edit' => [
            'controller' => 'FunctiesController',
            'method' => 'edit',
        ],
        'functie-save' => [
            'controller' => 'FunctiesController',
            'method' => 'save',
        ],
        'aandachtspunten' => [
            'controller' => 'AandachtspuntenController',
            'method' => 'index',
        ],
        'aandachtspunt-edit' => [
            'controller' => 'AandachtspuntenController',
            'method' => 'edit',
        ],
        'verdiepingsvragen' => [
            'controller' => 'VerdiepingsvragenController',
            'method' => 'index',
        ],
        'verdieping-vraag-edit' => [
            'controller' => 'VerdiepingsvragenController',
            'method' => 'edit',
        ],
        '404' => [
            'controller' => 'ErrorController',
            'method' => 'notFound',
        ],
    ],
];
