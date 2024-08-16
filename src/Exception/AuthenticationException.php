<?php

declare(strict_types = 1);

namespace Mega\Exception;

use Exception;

class AuthenticationException extends Exception
{
    public function __construct()
    {
        $message = sprintf('Authentication Error: Invalid Credentials');
        parent::__construct($message);
    }
}
