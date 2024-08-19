<?php

declare(strict_types = 1);

namespace Mega\Controller;

use Mega\Entity\User;

abstract class AbstractController
{
    protected User $authenticatedUsed;

    public function setAuthenticatedUser(User $user): void
    {
        $this->authenticatedUsed = $user;
    }
}
