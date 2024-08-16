<?php

declare(strict_types = 1);

namespace MegaTests\Unit\Lib\Http;

use Lib\Http\Router;
use Lib\ServiceLocator;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testFindRouteShouldFindASimpleMatchingUri()
    {
        $routesDefinition = [
            [
                'route' => '/user',
                'method' => 'GET',
                'controller' => 'DummyUserController',
                'action' => 'index',
            ],
            [
                'route' => '/role/:slug:',
                'method' => 'GET',
                'controller' => 'DummyRoleController',
                'action' => 'index',
                'param' => ':slug:',
            ],
        ];

        $serviceLocator = $this->getMockBuilder(ServiceLocator::class)
            ->getMock();

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/user';

        $router = new Router($serviceLocator);
        $router->addRoutes($routesDefinition);
        $router->init();

        $result = $router->findRoute();
        $this->assertEquals($routesDefinition[0], $result);
    }

    public function testFindRouteShouldFindTheCorrectMatchingUri()
    {
        $routeDefinition = [
            'route' => '/role/:slug:',
            'method' => 'GET',
            'controller' => 'DummyRoleController',
            'action' => 'index',
            'param' => ':slug:',
        ];

        $routesDefinition = [
            [
                'route' => '/user',
                'method' => 'GET',
                'controller' => 'DummyUserController',
                'action' => 'index',
            ],
            $routeDefinition,
        ];

        $serviceLocator = $this->getMockBuilder(ServiceLocator::class)
            ->getMock();

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/role/user';

        $router = new Router($serviceLocator);
        $router->addRoutes($routesDefinition);
        $router->init();

        $routeDefinition['uriParams'] = [
            ':slug:' => 'user',
        ];

        $result = $router->findRoute();
        $this->assertEquals($routeDefinition, $result);
    }

    public function testFindRouteShouldNotFindAMatchingUriWhenMethodDoesNotMatch()
    {
        $routeDefinition = [
            'route' => '/role/:slug:',
            'method' => 'POST',
            'controller' => 'DummyRoleController',
            'action' => 'index',
            'param' => ':slug:',
        ];

        $routesDefinition = [
            [
                'route' => '/user',
                'method' => 'GET',
                'controller' => 'DummyUserController',
                'action' => 'index',
            ],
            $routeDefinition,
        ];

        $serviceLocator = $this->getMockBuilder(ServiceLocator::class)
            ->getMock();

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/role/user';

        $router = new Router($serviceLocator);
        $router->addRoutes($routesDefinition);
        $router->init();

        $routeDefinition['uriParams'] = [
            ':slug:' => 'user',
        ];

        $result = $router->findRoute();
        $this->assertEmpty($result);
    }
}
