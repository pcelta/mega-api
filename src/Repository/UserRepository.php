<?php

declare(strict_types = 1);

namespace Mega\Repository;

use DateTime;
use Mega\Entity\User;
use Mega\Exception\EntityNotFoundException;

class UserRepository extends AbstractRepository
{
    public function findByUsername(string $username): User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch();

        return $this->buildUserFromRow($row, false);
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
}
