<?php

declare(strict_types = 1);

namespace Mega\Service;

use DateTime;
use Lib\Uid;
use Mega\Entity\User;
use Mega\Exception\UsernameAlreadyInUseException;
use Mega\Repository\RoleRepository;
use Mega\Repository\UserAccessRepository;
use Mega\Repository\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository,
        protected UserAccessRepository $userAccessRepository
    ) {}

    public function createFromPostData(array $data): User
    {
        if ($this->userRepository->usernameExists($data['username'])) {
            throw new UsernameAlreadyInUseException($data['username']);
        }

        $uid = Uid::generate();
        $user = new User(null, $uid, $data['username'], password_hash($data['password'], PASSWORD_DEFAULT), true);
        $this->userRepository->persist($user);

        $commitedUser = $this->userRepository->findByUsername($user->getUsername());

        $roleUids = [];
        foreach ($data['roles'] as $role) {
            $roleUids[] = $role['uid'];
        }

        $roles = $this->roleRepository->findManyByUids($roleUids);
        $commitedUser->setRoles($roles);

        $this->userRepository->update($commitedUser);

        return $commitedUser;
    }

    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function getOneByUid(string $userUid): User
    {
        return $this->userRepository->findOneByUid($userUid);
    }

    public function update(User $user, array $data): User
    {
        $updatedUser = $user;
        if (!empty($data['password'])) {
            $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
        }

        if (!isset($data['roles'])) {
            return $updatedUser;
        }

        $roleUids = [];
        foreach ($data['roles'] as $role) {
            $roleUids[] = $role['uid'];
        }

        $roles = $this->roleRepository->findManyByUids($roleUids);
        $updatedUser->setRoles($roles);

        $this->userRepository->update($user);
        $updatedUser = $this->userRepository->findOneByUid($user->getUid());

        return $updatedUser;
    }

    public function disable(User $user): void
    {
        $this->userRepository->disable($user);
        $this->userAccessRepository->removeAllByUser($user);
    }
}
