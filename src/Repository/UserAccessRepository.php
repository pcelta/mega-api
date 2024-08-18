<?php

declare(strict_types = 1);

namespace Mega\Repository;

use Mega\Entity\User;
use Mega\Entity\UserAccess;
use Mega\Exception\EntityNotFoundException;
use Mega\Repository\EntityBuilder\UserAccessBuilder;
use PDO;

class UserAccessRepository extends AbstractRepository
{
    public function __construct(protected PDO $pdo, protected UserAccessBuilder $userAccessBuilder) {
        parent::__construct($pdo);
    }

    public function persist(UserAccess $userAccess): void
    {
        $sql = 'INSERT INTO user_access(fk_user, token, `type`, expires_at, created_at) VALUES (:fk_user, :token, :type, :expires_at, :created_at)';
        $stmt = $this->pdo->prepare($sql);

        $userId = $userAccess->getUser()->getId();
        $token = $userAccess->getToken();
        $type = $userAccess->getType();
        $expiresAt = $userAccess->getExpiresAt()->format('Y-m-d: H:i:s');
        $createdAt = $userAccess->getCreatedAt()->format('Y-m-d: H:i:s');

        $stmt->bindParam(':fk_user', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':expires_at', $expiresAt, PDO::PARAM_STR);
        $stmt->bindParam(':created_at', $createdAt, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function removeAllByUser(User $user): void
    {
        $sql = 'DELETE FROM user_access WHERE fk_user = :fk_user';
        $stmt = $this->pdo->prepare($sql);

        $userId = $user->getId();
        $stmt->bindParam(':fk_user', $userId, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function removeAccessTokenByUser(User $user): void
    {
        $sql = 'DELETE FROM user_access WHERE fk_user = :fk_user AND `type` = :type';
        $stmt = $this->pdo->prepare($sql);

        $userId = $user->getId();
        $type = UserAccess::TYPE_ACCESS;

        $stmt->bindParam(':fk_user', $userId, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function findValidRefreshToken(string $refreshToken): UserAccess
    {
        $sql = 'SELECT * FROM user_access WHERE `type` = :type AND token = :token AND NOW() <= expires_at';
        $stmt = $this->pdo->prepare($sql);

        $type = UserAccess::TYPE_REFRESH;

        $stmt->bindParam(':token', $refreshToken, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);

        $stmt->execute();
        $row = $stmt->fetch();
        if (!$row) {
            throw new EntityNotFoundException(UserAccess::class);
        }

        return $this->userAccessBuilder->buildFromRow($row);
    }
}
