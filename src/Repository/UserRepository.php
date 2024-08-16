<?php

declare(strict_types = 1);

namespace Mega\Repository;

use DateTime;
use Mega\Entity\User;

class UserRepository extends AbstractRepository
{
    public function findByUsername(string $username): User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch();

        $createdAt = Datetime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
        $updatedAt = Datetime::createFromFormat('Y-m-d H:i:s', $row['updated_at']);

        return new User((int) $row['id'], $row['uid'], $row['username'], $row['password'], $createdAt, $updatedAt);
    }
}
