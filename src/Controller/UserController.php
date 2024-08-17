<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Attribute\ActionPermissionAttribute;
use Lib\Http\JsonResponse;

class UserController
{
    #[ActionPermissionAttribute(['admin'])]
    public function create(): JsonResponse
    {
        return new JsonResponse(['message' => 'UserController::create action']);
    }
}
