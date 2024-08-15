<?php

declare(strict_types = 1);

namespace Mega\Service;

use Mega\Repository\RoleRepository;

class RoleService
{
    public function __construct(private RoleRepository $roleRepository)
    {
    }

    public function getAll(): array
    {
        return $this->roleRepository->fetchAll();
    }
}
