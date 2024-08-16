<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Mega\Service\RoleService;

class RoleController
{
    public function __construct(private RoleService $roleService) {}

    public function listOne(Request $request): Response
    {
        $roleSlug = $request->getParam(':slug:');
        $roles = $this->roleService->getAll();
        return new JsonResponse($roles);
    }
}
