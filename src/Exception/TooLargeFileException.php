<?php

declare(strict_types = 1);

namespace Mega\Exception;

use Exception;

class TooLargeFileException extends Exception
{
    public function __construct($currentFileSize, $limit)
    {
        $message = sprintf('The file provided is too large. Size: %d - Limit allowed', $currentFileSize, $limit);
        parent::__construct($message);
    }
}
