<?php

declare(strict_types = 1);

namespace Lib\Http;

use Lib\ServiceLocator;
use Mega\Service\AuthService;

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

    public function findRoute(): array
    {
        foreach ($this->routes as $routeConfig) {
            if ($this->uri === $routeConfig['route'] && $this->httpMethod === $routeConfig['method']) {
                return $routeConfig;
            }

            if (!isset($routeConfig['param'])) {
                continue;
            }

            $pattern = str_replace($routeConfig['param'], '(.*[a-zA-Z])', $routeConfig['route']);
            $pattern = sprintf('#^%s#', $pattern);
            preg_match($pattern, $this->uri, $matches);
            if (count($matches) === 0) {
                continue;
            }

            if ($this->httpMethod !== $routeConfig['method']) {
                continue;
            }

            $routeConfig['uriParams'] = [
                $routeConfig['param'] => $matches[1],
            ];

            return $routeConfig;
        }

        return [];
    }

    public function dispatch(): void
    {
        $routeConfig = $this->findRoute();
        if ($routeConfig) {
            $this->dispatchController($routeConfig);

            return;
        }

        $notFoundResponse = new JsonResponse();
        $notFoundResponse->setData(['message' => 'Route Not Found']);;
        $notFoundResponse->setStatusCode(Response::HTTP_STATUS_NOT_FOUND);
        $notFoundResponse->send();
    }

    private function dispatchController(array $routeConfig): void
    {
        if (isset($routeConfig['uriParams'])) {
            foreach ($routeConfig['uriParams'] as $paramName => $paramValue) {
                $this->request->setCustomParam($paramName, $paramValue);
            }
        }

        if (!$this->hasPermissionToAccess($routeConfig)) {
            $notFoundResponse = new JsonResponse();
            $notFoundResponse->setData(['message' => 'Access Denied']);;
            $notFoundResponse->setStatusCode(Response::HTTP_STATUS_FORBIDDEN);
            $notFoundResponse->send();

            return;
        }

        $controller = $this->serviceLocator->get($routeConfig['controller']);
        $response = $controller->{$routeConfig['action']}($this->request);
        $response->send();
    }

    protected function hasPermissionToAccess($routeConfig): bool
    {
        $userToken = str_replace('Bearer: ', '', $this->request->getAuthorizationHeader());
        $authService = $this->serviceLocator->get(AuthService::class);

        return $authService->accessTokenCanAccessRoute($userToken, $routeConfig);
    }
}
