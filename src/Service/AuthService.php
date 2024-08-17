<?php

declare(strict_types = 1);

namespace Mega\Service;

use Mega\Entity\Role;
use Mega\Entity\User;
use Mega\Entity\UserIdentity;
use Mega\Exception\AuthenticationException;
use Mega\Exception\EntityNotFoundException;
use Mega\Repository\RoleRepository;
use Mega\Repository\UserRepository;

class AuthService
{
    public function __construct(protected UserRepository $userRepository, protected RoleRepository $roleRepository) {}

    public function authenticate(UserIdentity $userIdentity): User
    {
        $user = $this->userRepository->findByUsername($userIdentity->getUsername(), false);
        if (!password_verify($userIdentity->getPassword(), $user->getPassword())) {
            throw new AuthenticationException();
        }

        return $user;
    }

    public function accessTokenCanAccessRoute(string $accessToken, array $routeConfig)
    {
        if (in_array(Role::ROLE_ANONYMOUS, $routeConfig['allowed'])) {
            return true;
        }

        try {
            $user = $this->userRepository->findByAccessToken($accessToken);
        } catch (EntityNotFoundException $e) {
            return false;
        }

        $userRoles = $this->roleRepository->findByUser($user);
        foreach ($userRoles as $role) {
            if (in_array($role->getSlug(), $routeConfig['allowed'])) {
                return true;
            }
        }

        return false;
    }
}
