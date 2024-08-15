<?php

declare(strict_types = 1);

namespace Mega\Repository;

use PDO;

class AbstractRepository
{
    public function __construct(protected PDO $pdo) {}
}
