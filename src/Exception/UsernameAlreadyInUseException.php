<?php

declare(strict_types = 1);

namespace Mega\Exception;

use Exception;

class UsernameAlreadyInUseException extends Exception
{
    public function __construct(string $username)
    {
        $message = sprintf('Username is already in use: %s', $username);
        parent::__construct($message);
    }
}
