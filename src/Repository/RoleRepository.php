<?php

declare(strict_types = 1);

namespace Mega\Repository;

class RoleRepository extends AbstractRepository
{
    public function fetchAll(): array
    {
        $stmt = $this->pdo->query('SELECT uid, name, description FROM role');
        return $stmt->fetchAll();
    }
}
