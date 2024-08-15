<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Response;
use Mega\Service\RoleService;

class RoleController
{
    public function __construct(private RoleService $roleService) {}

    public function list(): Response
    {
        $roles = $this->roleService->getAll();
        return new JsonResponse($roles);
    }
}
