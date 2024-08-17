<?php

declare(strict_types = 1);

namespace Mega\Exception;

use Exception;

class EntityNotFoundException extends Exception
{
    public function __construct(string $entityName)
    {
        $message = sprintf('Entity: %s Not Found', $entityName);
        parent::__construct($message);
    }
}
