<?php

declare(strict_types = 1);

namespace Mega\Service;

use DateTime;
use Lib\Uid;
use Mega\Entity\User;
use Mega\Entity\UserAccess;
use Mega\Exception\EntityNotFoundException;
use Mega\Exception\InvalidRefreshTokenException;
use Mega\Repository\UserAccessRepository;
use Mega\Repository\UserRepository;

class UserAccessService
{
    public function __construct(protected UserAccessRepository $userAccessRepository, protected UserRepository $userRepository) {}

    public function regenerateTokens(User $user): array
    {
        $this->cleanTokens($user);

        $accessToken = Uid::generate();
        $currentDatetime = new DateTime();

        $accessTokenExpiresAt = new DateTime();
        $accessTokenExpiresAt->modify('+1 day');
        $userAccess = new UserAccess(null, $user, $accessToken, UserAccess::TYPE_ACCESS, $accessTokenExpiresAt, $currentDatetime, $currentDatetime);

        $refreshToken = Uid::generate();
        $refreshTokenExpiresAt = new DateTime();
        $refreshTokenExpiresAt->modify('+5 days');
        $userAccessToRefresh = new UserAccess(null, $user, $refreshToken, UserAccess::TYPE_REFRESH, $refreshTokenExpiresAt, $currentDatetime, $currentDatetime);

        $this->userAccessRepository->persist($userAccess);
        $this->userAccessRepository->persist($userAccessToRefresh);

        return [$userAccess, $userAccessToRefresh];
    }

    protected function cleanTokens(User $user): void
    {
        $this->userAccessRepository->removeAllByUser($user);
    }

    public function renewAccessTokenByRefreshToken(string $refreshToken): UserAccess
    {
        try {
            $userAccess = $this->userAccessRepository->findValidRefreshToken($refreshToken);
        } catch (EntityNotFoundException $e) {
            throw new InvalidRefreshTokenException();
        }

        $user = $this->userRepository->findByToken($refreshToken, 'refresh');
        $accessToken = Uid::generate();
        $currentDatetime = new DateTime();

        $accessTokenExpiresAt = new DateTime();
        $accessTokenExpiresAt->modify('+1 day');
        $userAccess = new UserAccess(null, $user, $accessToken, UserAccess::TYPE_ACCESS, $accessTokenExpiresAt, $currentDatetime, $currentDatetime);

        $this->userAccessRepository->removeAccessTokenByUser($user);
        $this->userAccessRepository->persist($userAccess);

        return $userAccess;
    }
}
