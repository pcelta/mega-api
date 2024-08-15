<?php

declare(strict_types = 1);

namespace Lib;

class ServiceLocator
{
    protected static array $loadedServices = [];
    private static array $servicesDefinitions = [];

    public function get(string $name)
    {
        if (isset(self::$loadedServices[$name])) {
            return self::$loadedServices[$name];
        }

        return $this->loadService(self::$servicesDefinitions[$name]);
    }

    public static function set(string $name, $instance): void
    {
        self::$loadedServices[$name] = $instance;
    }

    public function init(): void
    {
        self::$servicesDefinitions = require_once ROOT_DIR . '/config/services.php';
    }

    private function loadService(array $serviceDefinition)
    {
        if (!isset($serviceDefinition['args'])) {
            $service = $serviceDefinition['factory']();
            ServiceLocator::set($serviceDefinition['name'], $service);

            return $service;
        }

        $args = [];
        foreach ($serviceDefinition['args'] as $serviceName) {
            $service = self::get($serviceName);
            if ($service !== null) {
                $args[] = $service;

                continue;
            }

            $service = $this->loadService(self::$servicesDefinitions[$serviceName]);

            $args[] = $service;
        }

        $service = new $serviceDefinition['name'](...$args);
        ServiceLocator::set($serviceName, $service);

        return $service;
    }
}
