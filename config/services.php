<?php

use Lib\ServiceLocator;
use Mega\Controller\RoleController;
use Mega\Controller\UserController;
use Mega\Repository\RoleRepository;
use Mega\Repository\UserRepository;
use Mega\Service\RoleService;
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

    //repositories
    RoleRepository::class => [
        'name' => RoleRepository::class,
        'args' => [
            PDO::class
        ]
    ],
    UserRepository::class => [
        'name' => UserRepository::class,
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
    UserService::class => [
        'name' => UserService::class,
        'args' => [
            UserRepository::class
        ]
    ],

    // controllers
    RoleController::class => [
        'name' => RoleController::class,
        'args' => [
            RoleService::class
        ]
    ],
    UserController::class => [
        'name' => UserController::class,
        'args' => [
            UserService::class
        ]
    ],
];
