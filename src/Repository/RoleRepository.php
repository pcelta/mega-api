<?php

declare(strict_types = 1);

namespace Mega\Repository;

use DateTime;
use Mega\Entity\Role;
use Mega\Entity\User;
use Mega\Exception\EntityNotFoundException;
use PDO;

class RoleRepository extends AbstractRepository
{
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

        return $this->buildRoleFromRow($row);
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
            $roles[] = $this->buildRoleFromRow($row);
        }

        return $roles;
    }

    private function buildRoleFromRow(array $row): Role
    {
        $this->transformStringDateToDatetime($row);

        return new Role((int) $row['id'], $row['uid'], $row['name'], $row['slug'], $row['created_at'], $row['updated_at']);
    }
}
