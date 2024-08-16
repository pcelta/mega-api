<?php

declare(strict_types = 1);

namespace Mega\Service;

use Mega\Entity\User;
use Mega\Entity\UserIdentity;
use Mega\Exception\AuthenticationException;
use Mega\Repository\UserRepository;

class AuthService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function authenticate(UserIdentity $userIdentity): User
    {
        $user = $this->userRepository->findByUsername($userIdentity->getUsername());
        if (!password_verify($userIdentity->getPassword(), $user->getPassword())) {
            throw new AuthenticationException();
        }

        return $user;
    }
}
