<?php

declare(strict_types = 1);

namespace Mega\Repository;

use DateTime;
use Mega\Entity\Role;
use Mega\Entity\User;
use Mega\Exception\EntityNotFoundException;
use Mega\Repository\EntityBuilder\RoleBuilder;
use PDO;

class RoleRepository extends AbstractRepository
{
    public function __construct(protected PDO $pdo, protected RoleBuilder $roleBuilder) {
        parent::__construct($pdo);
    }

    public function findBySlug(string $slug): Role
    {
        $sql = 'SELECT * FROM role WHERE slug = :slug ';
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':slug', $slug);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row) {
            throw new EntityNotFoundException(Role::class);
        }

        return $this->roleBuilder->buildFromRow($row);
    }

    public function findManyByUids(array $uids): array
    {
        $inUids = implode('\',\'', $uids);
        $sql = "SELECT * FROM role WHERE uid IN ('$inUids') ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $roles = [];
        foreach ($rows as $row) {
            $roles[] = $this->roleBuilder->buildFromRow($row);
        }

        return $roles;
    }

    public function findByUser(User $user): array
    {
        $sql = 'SELECT r.* FROM role r ';
        $sql .= 'INNER JOIN user_role ur ON r.id=ur.fk_role ';
        $sql .= 'WHERE ur.fk_user = :fk_user ';

        $stmt = $this->pdo->prepare($sql);

        $userId = $user->getId();

        $stmt->bindParam(':fk_user', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll();
        $roles = [];
        foreach ($rows as $row) {
            $roles[] = $this->roleBuilder->buildFromRow($row);
        }

        return $roles;
    }
}
