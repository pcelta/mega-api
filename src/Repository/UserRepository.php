<?php

declare(strict_types = 1);

namespace Mega\Repository;

use DateTime;
use Mega\Entity\User;
use Mega\Exception\EntityNotFoundException;
use Mega\Repository\EntityBuilder\RoleBuilder;
use Mega\Repository\EntityBuilder\UserBuilder;
use PDO;

class UserRepository extends AbstractRepository
{
    public function __construct(protected PDO $pdo, protected UserBuilder $userBuilder, protected RoleBuilder $roleBuilder) {
        parent::__construct($pdo);
    }

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

    public function findByUsername(string $username, bool $cleanPassword = true): User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $row = $stmt->fetch();

        if ($cleanPassword) {
            $row['password'] = User::PASSWORD_CLEAN_STATE;
        }


        return $this->userBuilder->buildFromRow($row);
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        return $stmt->fetch() !== false;;
    }

    public function findByToken(string $acessToken, string $type): User
    {
        $sql = 'SELECT u.* FROM user u ';
        $sql .= 'INNER JOIN user_access ua ON ua.fk_user=u.id ';
        $sql .= 'WHERE token = :token AND `type` = :type AND NOW() <= expires_at';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':token', $acessToken);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row) {
            throw new EntityNotFoundException(User::class);
        }

        return $this->userBuilder->buildFromRow($row);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user');
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $users = [];
        foreach ($rows as $row) {
            $users[] = $this->userBuilder->buildFromRow($row);
        }

        return $users;
    }

    public function findOneByUid(string $uid): User
    {
        $sql = <<<'QUERY'
            SELECT u.*, r.id as r__id, r.name as r__name, r.slug as r__slug, r.uid as r__uid, r.created_at as r__created_at, r.updated_at as r__updated_at FROM user u
            LEFT JOIN user_role ur ON ur.fk_user=u.id
            LEFT JOIN role r ON r.id=ur.fk_role WHERE u.uid = :uid
QUERY;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':uid', $uid);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        if (!$rows) {
            throw new EntityNotFoundException(User::class);
        }

        $user = $this->userBuilder->buildFromRow($rows[0]);
        $roles = [];
        foreach ($rows as $row) {
            $roles[] = $this->roleBuilder->buildFromRow($row, 'r__');
        }
        $user->setRoles($roles);

        return $user;
    }

    public function update(User $user): void
    {
        if ($user->getPassword() !== User::PASSWORD_CLEAN_STATE) {
            $stmt = $this->pdo->prepare('UPDATE user SET `password` = :password WHERE uid = :uid');
            $uid = $user->getUid();
            $password = $user->getPassword();

            $stmt->bindParam(':uid', $uid);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
        }

        if (!$user->getRoles()) {
            return;
        }

        $this->removeAllUserRoles($user);
        $this->persistUserRoles($user);
    }

    private function removeAllUserRoles(User $user): void
    {
        $userId = $user->getId();
        $stmt = $this->pdo->prepare('DELETE FROM user_role WHERE fk_user = :fk_user');
        $stmt->bindParam(':fk_user', $userId);
        $stmt->execute();
    }

    public function persistUserRoles(User $user): void
    {
        foreach ($user->getRoles() as $role) {
            $sql = 'INSERT INTO user_role(fk_user, fk_role) VALUES (:fk_user, :fk_role)';
            $stmt = $this->pdo->prepare($sql);

            $userUid = $user->getId();
            $roleId = $role->getId();

            $stmt->bindParam(':fk_user', $userUid, PDO::PARAM_INT);
            $stmt->bindParam(':fk_role', $roleId, PDO::PARAM_INT);

            $stmt->execute();
        }
    }

    public function disable(User $user): void
    {
        $userUid = $user->getUid();
        $stmt = $this->pdo->prepare('UPDATE user SET `is_active` = 0 WHERE uid = :uid');
        $stmt->bindParam(':uid', $userUid);
        $stmt->execute();
    }
}
