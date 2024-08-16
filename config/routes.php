<?php

use Mega\Controller\AuthController;
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
        'route' => '/auth',
        'method' => 'POST',
        'controller' => AuthController::class,
        'action' => 'authenticate',
    ],
    [
        'route' => '/role/:slug:',
        'method' => 'GET',
        'controller' => RoleController::class,
        'action' => 'listOne',
        'param' => ':slug:',
    ],
    [
        'route' => '/user',
        'method' => 'POST',
        'controller' => UserController::class,
        'action' => 'create',
    ],
];
