<?php

declare(strict_types = 1);

namespace Mega\Repository;

use DateTime;
use Mega\Entity\User;
use Mega\Exception\EntityNotFoundException;
use PDO;

class UserRepository extends AbstractRepository
{
    public function persist(User $user): void
    {
        $sql = 'INSERT INTO user(uid, username, `password`) VALUES (:uid, :username, :password)';
        $stmt = $this->pdo->prepare($sql);

        $userUid = $user->getUid();
        $username = $user->getUsername();
        $password = $user->getPassword();

        $stmt->bindParam(':uid', $userUid, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function findByUsername(string $username): User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch();

        return $this->buildUserFromRow($row, false);
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch() !== false;;
    }

    public function findByAccessToken(string $acessToken): User
    {
        $sql = 'SELECT u.* FROM user u ';
        $sql .= 'INNER JOIN user_access ua ON ua.fk_user=u.id ';
        $sql .= 'WHERE token = :token AND `type` = "access" ';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $acessToken);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row) {
            throw new EntityNotFoundException(User::class);
        }

        return $this->buildUserFromRow($row);
    }

    private function buildUserFromRow(array $row, bool $removeSensitiveData = true): User
    {
        $this->transformStringDateToDatetime($row);

        if ($removeSensitiveData === true) {
            $row['password'] = 'clean-password-for-security-reasons';
        }

        return new User((int) $row['id'], $row['uid'], $row['username'], $row['password'], $row['created_at'], $row['updated_at']);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user');
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $users = [];
        foreach ($rows as $row) {
            $users[] = $this->buildUserFromRow($row);
        }

        return $users;
    }

    public function findOneByUid(string $uid): User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE uid = :uid');
        $stmt->bindParam(':uid', $uid);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row) {
            throw new EntityNotFoundException(User::class);
        }

        return $this->buildUserFromRow($row);
    }
}
