<?php

declare(strict_types = 1);

namespace Mega\Service;

use DateTime;
use Lib\Uid;
use Mega\Entity\User;
use Mega\Entity\UserAccess;
use Mega\Repository\UserAccessRepository;

class UserAccessService
{
    public function __construct(protected UserAccessRepository $userAccessRepository) {}

    public function regenerateTokens(User $user): array
    {
        $this->cleanTokens($user);

        $accessToken = Uid::generate();
        $currentDatetime = new DateTime();

        $accessTokenExpiresAt = new DateTime();
        $accessTokenExpiresAt->modify('+6 hour');
        $userAccess = new UserAccess(null, $user, $accessToken, UserAccess::TYPE_ACCESS, $accessTokenExpiresAt, $currentDatetime, $currentDatetime);

        $refreshToken = Uid::generate();
        $refreshTokenExpiresAt = new DateTime();
        $refreshTokenExpiresAt->modify('+3 day');
        $userAccessToRefresh = new UserAccess(null, $user, $refreshToken, UserAccess::TYPE_REFRESH, $refreshTokenExpiresAt, $currentDatetime, $currentDatetime);

        $this->userAccessRepository->persist($userAccess);
        $this->userAccessRepository->persist($userAccessToRefresh);

        return [$userAccess, $userAccessToRefresh];
    }

    protected function cleanTokens(User $user): void
    {
        $this->userAccessRepository->removeAllByUser($user);
    }
}
