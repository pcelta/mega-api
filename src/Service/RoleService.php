<?php

declare(strict_types = 1);

namespace Mega\Service;

use Mega\Entity\Role;
use Mega\Repository\RoleRepository;

class RoleService
{
    public function __construct(private RoleRepository $roleRepository) {}

    public function getBySlug(string $slug): Role
    {
        return $this->roleRepository->findBySlug($slug);
    }
}
