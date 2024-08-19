<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Request;
use Lib\Http\Response;
use Mega\Exception\EntityNotFoundException;
use Mega\Service\RoleService;

class RoleController extends AbstractController
{
    public function __construct(private RoleService $roleService) {}

    public function listOne(Request $request): Response
    {
        $roleSlug = $request->getParam(':slug:');
        try {
            $role = $this->roleService->getBySlug($roleSlug);

            return new JsonResponse($role->toArray());
        } catch (EntityNotFoundException $e) {
            return JsonResponse::createNotFound();
        }
    }
}
