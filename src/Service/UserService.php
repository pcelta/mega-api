<?php

declare(strict_types = 1);

namespace Mega\Service;

use DateTime;
use Lib\Uid;
use Mega\Entity\User;
use Mega\Exception\UsernameAlreadyInUseException;
use Mega\Repository\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function createFromPostData(array $data): User
    {
        if ($this->userRepository->usernameExists($data['username'])) {
            throw new UsernameAlreadyInUseException($data['username']);
        }

        $uid = Uid::generate();
        $user = new User(null, $uid, $data['username'], password_hash($data['password'], PASSWORD_DEFAULT));
        $this->userRepository->persist($user);

        $commitedUser = $this->userRepository->findByUsername($user->getUsername());

        return $commitedUser;
    }

    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }
}
