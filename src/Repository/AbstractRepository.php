<?php

declare(strict_types = 1);

namespace Mega\Repository;

use Lib\ServiceLocator;
use PDO;

class AbstractRepository
{
    public function __construct(protected ?PDO $pdo)
    {
        if ($pdo === null) {
            $this->pdo = ServiceLocator::get(PDO::class);
        }
    }
}
