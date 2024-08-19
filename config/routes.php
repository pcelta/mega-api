<?php

use Mega\Controller\AuthController;
use Mega\Controller\FileController;
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
        'route' => '/auth/refresh-token',
        'method' => 'POST',
        'controller' => AuthController::class,
        'action' => 'refreshToken',
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
    [
        'route' => '/user/:uid:',
        'param' => ':uid:',
        'method' => 'GET',
        'controller' => UserController::class,
        'action' => 'listOne',
        'allowed' => [Role::ROLE_ADMIN],
    ],
    [
        'route' => '/user/:uid:',
        'param' => ':uid:',
        'method' => 'PATCH',
        'controller' => UserController::class,
        'action' => 'patch',
        'allowed' => [Role::ROLE_ADMIN],
    ],
    [
        'route' => '/user/:uid:',
        'param' => ':uid:',
        'method' => 'DELETE',
        'controller' => UserController::class,
        'action' => 'disable',
        'allowed' => [Role::ROLE_ADMIN],
    ],
    [
        'route' => '/file',
        'method' => 'POST',
        'controller' => FileController::class,
        'action' => 'upload',
        'allowed' => [Role::ROLE_ADMIN, Role::ROLE_USER],
    ],

    [
        'route' => '/file/:uid:',
        'param' => ':uid:',
        'method' => 'GET',
        'controller' => FileController::class,
        'action' => 'listOne',
        'allowed' => [Role::ROLE_ADMIN, Role::ROLE_USER],
    ],
];
