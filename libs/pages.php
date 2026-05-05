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
        'otp-verify' => [
            'controller' => 'OTPController',
            'method' => 'verifyCode',
        ],
        'otp-logout' => [
            'controller' => 'OTPController',
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
        'verdieping-vraag-save' => [
            'controller' => 'VerdiepingsvragenController',
            'method' => 'save',
        ],
        'verdieping-vraag-delete' => [
            'controller' => 'VerdiepingsvragenController',
            'method' => 'delete',
        ],
        'organisaties' => [
            'controller' => 'OrganisatiesController',
            'method' => 'index',
        ],
        'organisatie-edit' => [
            'controller' => 'OrganisatiesController',
            'method' => 'edit',
        ],
        'organisatie-save' => [
            'controller' => 'OrganisatiesController',
            'method' => 'save',
        ],
        'organisatie-delete' => [
            'controller' => 'OrganisatiesController',
            'method' => 'delete',
        ],
        'hulpbronnen' => [
            'controller' => 'HulpbronnenController',
            'method' => 'index',
        ],
        'hulpbron-edit' => [
            'controller' => 'HulpbronnenController',
            'method' => 'edit',
        ],
        'hulpbron-save' => [
            'controller' => 'HulpbronnenController',
            'method' => 'save',
        ],
        'hulpbron-delete' => [
            'controller' => 'HulpbronnenController',
            'method' => 'delete',
        ],
        'leefgebied-hulpbron-koppelingen' => [
            'controller' => 'LeefgebiedHulpbronKoppelingenController',
            'method' => 'index',
        ],
        'leefgebied-hulpbron-koppeling-save' => [
            'controller' => 'LeefgebiedHulpbronKoppelingenController',
            'method' => 'save',
        ],
        'leefgebied-hulpbron-koppeling-delete' => [
            'controller' => 'LeefgebiedHulpbronKoppelingenController',
            'method' => 'delete',
        ],
        'verdieping-koppelingen' => [
            'controller' => 'VerdiepingKoppelingenController',
            'method' => 'index',
        ],
        'verdieping-koppeling-save' => [
            'controller' => 'VerdiepingKoppelingenController',
            'method' => 'save',
        ],
        'verdieping-koppeling-delete' => [
            'controller' => 'VerdiepingKoppelingenController',
            'method' => 'delete',
        ],
        'vragenlijsten' => [
            'controller' => 'VragenlijstenController',
            'method' => 'index',
        ],
        'vragenlijst-vraag-edit' => [
            'controller' => 'VragenlijstenController',
            'method' => 'editVraag',
        ],
        'vragenlijst-vraag-save' => [
            'controller' => 'VragenlijstenController',
            'method' => 'saveVraag',
        ],
        'vragenlijst-vraag-delete' => [
            'controller' => 'VragenlijstenController',
            'method' => 'deleteVraag',
        ],
        '404' => [
            'controller' => 'ErrorController',
            'method' => 'notFound',
        ],
    ],
];
