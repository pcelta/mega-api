<?php

use Lib\ServiceLocator;
use Mega\Controller\RoleController;
use Mega\Repository\RoleRepository;
use Mega\Service\RoleService;

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
    RoleRepository::class => [
        'name' => RoleRepository::class,
        'args' => [
            PDO::class
        ]
    ],
    RoleService::class => [
        'name' => RoleService::class,
        'args' => [
            RoleRepository::class
        ]
    ],

    // controllers
    RoleController::class => [
        'name' => RoleController::class,
        'args' => [
            RoleService::class
        ]
    ],
];
