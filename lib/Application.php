<?php

declare(strict_types = 1);

namespace Lib;

use Lib\Http\Router;
use PDO;
use PDOException;

class Application
{
    public function run(): void
    {
        $this->onBoostrap();
    }

    private function onBoostrap(): void
    {
        $serviceLocator = new ServiceLocator();
        $serviceLocator->init();

        $router = new Router(new ServiceLocator());
        $router->init();
        $routes = require_once ROOT_DIR . '/config/routes.php';
        $router->addRoutes($routes);
        $router->dispatch();
    }
}
