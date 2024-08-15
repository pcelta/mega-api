<?php

declare(strict_types = 1);

namespace Lib;

use Lib\Http\Router;

class Application
{
    public function run(): void
    {
        $this->onBoostrap();
    }

    private function onBoostrap(): void
    {
        $router = new Router();
        $router->init();
        $routes = require_once ROOT_DIR . '/config/routes.php';
        $router->addRoutes($routes);
        $router->dispatch();
    }
}
