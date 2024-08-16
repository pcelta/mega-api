<?php

declare(strict_types = 1);

namespace Lib\ServiceLocator;

use Exception;

class ServiceNotFoundException extends Exception
{
    public function __construct($serviceName)
    {
        $message = sprintf('Service[%s] was not found. Did you forget to define it in: config/services.php?', $serviceName);
        parent::__construct($message);
    }
}
