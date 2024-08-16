<?php

declare(strict_types = 1);

namespace Lib\Http;

use Lib\ServiceLocator;
use PHPUnit\Util\Json;

class Router
{
    private array $routes;
    private string $uri;
    private string $httpMethod;
    private Request $request;

    public function __construct(private ServiceLocator $serviceLocator) {}

    public function init(): void
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $uriWithQueryString = $_SERVER['REQUEST_URI'];
        $this->uri = explode('?', $uriWithQueryString)[0];
        $this->request = new Request($_SERVER, $_REQUEST, $_GET, $_POST);
    }


    public function addRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    public function dispatch(): void
    {
        foreach ($this->routes as $routeConfig) {
            if ($this->uri === $routeConfig['route'] && $this->httpMethod === $routeConfig['method']) {
                $this->dispatchController($routeConfig);

                return;
            }
        }

        $notFoundResponse = new JsonResponse();
        $notFoundResponse->setData(['message' => 'Route Not Found']);;
        $notFoundResponse->setStatusCode(Response::HTTP_STATUS_NOT_FOUND);
        $notFoundResponse->send();
    }

    private function dispatchController(array $routeConfig): void
    {
        $controller = $this->serviceLocator->get($routeConfig['controller']);
        $response = $controller->{$routeConfig['action']}($this->request);
        $response->send();
    }
}
