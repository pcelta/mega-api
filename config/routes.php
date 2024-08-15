<?php

use Mega\Controller\HealthCheckController;
use Mega\Controller\RoleController;
use Mega\Controller\UserController;

return [
    [
        'route' => '/health-check',
        'method' => 'GET',
        'controller' => HealthCheckController::class,
        'action' => 'index',
    ],
    [
        'route' => '/role',
        'method' => 'GET',
        'controller' => RoleController::class,
        'action' => 'list',
    ],
    [
        'route' => '/user',
        'method' => 'POST',
        'controller' => UserController::class,
        'action' => 'create',
    ],
];
