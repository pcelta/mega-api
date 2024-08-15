<?php

use Mega\Controller\HealthCheckController;

return [
    [
        'route' => '/health-check',
        'method' => 'GET',
        'controller' => HealthCheckController::class,
        'action' => 'index',
    ],
];
