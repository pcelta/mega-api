<?php

use Mega\Controller\AuthController;
use Mega\Controller\HealthCheckController;
use Mega\Controller\RoleController;
use Mega\Controller\UserController;
use Mega\Entity\Role;

return [
    [
        'route' => '/health-check',
        'method' => 'GET',
        'controller' => HealthCheckController::class,
        'action' => 'index',
        'allowed' => [Role::ROLE_ADMIN, Role::ROLE_USER, Role::ROLE_ANONYMOUS],
    ],
    [
        'route' => '/auth',
        'method' => 'POST',
        'controller' => AuthController::class,
        'action' => 'authenticate',
        'allowed' => [Role::ROLE_ANONYMOUS],
    ],
    [
        'route' => '/role/:slug:',
        'method' => 'GET',
        'controller' => RoleController::class,
        'action' => 'listOne',
        'param' => ':slug:',
        'allowed' => [Role::ROLE_ADMIN],
    ],
    [
        'route' => '/user',
        'method' => 'POST',
        'controller' => UserController::class,
        'action' => 'create',
        'allowed' => [Role::ROLE_ADMIN],
    ],
    [
        'route' => '/user',
        'method' => 'GET',
        'controller' => UserController::class,
        'action' => 'listAll',
        'allowed' => [Role::ROLE_ADMIN],
    ],
];
