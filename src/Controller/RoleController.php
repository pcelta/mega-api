<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Lib\Http\JsonResponse;
use Lib\Http\Response;

class RoleController
{
    public function list(): Response
    {
        return new JsonResponse();
    }
}
