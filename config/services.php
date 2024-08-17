<?php

use Lib\SchemaValidator;
use Mega\Controller\AuthController;
use Mega\Controller\RoleController;
use Mega\Controller\UserController;
use Mega\Repository\EntityBuilder\RoleBuilder;
use Mega\Repository\EntityBuilder\UserBuilder;
use Mega\Repository\RoleRepository;
use Mega\Repository\UserAccessRepository;
use Mega\Repository\UserRepository;
use Mega\Service\AuthService;
use Mega\Service\RoleService;
use Mega\Service\UserAccessService;
use Mega\Service\UserService;

return [
    PDO::class => [
        'name' => PDO::class,
        'factory' => function() {
            $dbconfig = require_once ROOT_DIR . '/config/database.php';
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $dbconfig['host'], $dbconfig['dbname'], $dbconfig['charset']);

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            try {
                return new PDO($dsn, $dbconfig['user'], $dbconfig['pass'], $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int) $e->getCode());
            }
        }
    ],

    // entity builders
    RoleBuilder::class => [
        'name' => RoleBuilder::class,
        'args' => []
    ],
    UserBuilder::class => [
        'name' => UserBuilder::class,
        'args' => []
    ],

    // repositories
    RoleRepository::class => [
        'name' => RoleRepository::class,
        'args' => [
            PDO::class,
            RoleBuilder::class
        ]
    ],
    UserRepository::class => [
        'name' => UserRepository::class,
        'args' => [
            PDO::class,
            UserBuilder::class,
            RoleBuilder::class
        ]
    ],
    UserAccessRepository::class => [
        'name' => UserAccessRepository::class,
        'args' => [
            PDO::class
        ]
    ],

    // services
    RoleService::class => [
        'name' => RoleService::class,
        'args' => [
            RoleRepository::class
        ]
    ],
    AuthService::class => [
        'name' => AuthService::class,
        'args' => [
            UserRepository::class,
            RoleRepository::class,
        ]
    ],
    UserAccessService::class => [
        'name' => UserAccessService::class,
        'args' => [
            UserAccessRepository::class
        ]
    ],
    UserService::class => [
        'name' => UserService::class,
        'args' => [
            UserRepository::class,
            RoleRepository::class
        ],
    ],

    // controllers
    RoleController::class => [
        'name' => RoleController::class,
        'args' => [
            RoleService::class
        ],
    ],
    AuthController::class => [
        'name' => AuthController::class,
        'args' => [
            AuthService::class,
            UserAccessService::class,
        ]
    ],
    UserController::class => [
        'name' => UserController::class,
        'args' => [
            SchemaValidator::class,
            UserService::class,
        ],
    ],

    // libs
    SchemaValidator::class => [
        'name' => SchemaValidator::class,
        'args' => [],
    ],
];
