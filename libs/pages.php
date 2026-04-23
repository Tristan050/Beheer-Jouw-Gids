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
        'leefgebied-delete' => [
            'controller' => 'LeefgebiedenController',
            'method' => 'delete',
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
        'functie-delete' => [
            'controller' => 'FunctiesController',
            'method' => 'delete',
        ],
        'aandachtspunten' => [
            'controller' => 'AandachtspuntenController',
            'method' => 'index',
        ],
        'aandachtspunt-edit' => [
            'controller' => 'AandachtspuntenController',
            'method' => 'edit',
        ],
        'aandachtspunt-save' => [
            'controller' => 'AandachtspuntenController',
            'method' => 'save',
        ],
        'aandachtspunt-delete' => [
            'controller' => 'AandachtspuntenController',
            'method' => 'delete',
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
