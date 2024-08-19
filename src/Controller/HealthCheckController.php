<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Request;

class HealthCheckController
{
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(['message' => 'Mega API is up and running!']);
    }
}
