<?php

declare(strict_types = 1);

namespace Mega\Repository\EntityBuilder;

use Mega\Entity\Role;

class RoleBuilder extends AbstractEntityBuilder
{
    public function buildFromRow(array $row, string $prefix = ''): Role
    {
        $row['id'] = $row[$prefix . 'id'];
        $row['uid'] = $row[$prefix . 'uid'];
        $row['name'] = $row[$prefix . 'name'];
        $row['slug'] = $row[$prefix . 'slug'];
        $row['created_at'] = $row[$prefix . 'created_at'];
        $row['updated_at'] = $row[$prefix . 'updated_at'];

        $this->transformStringDateToDatetime($row);

        return new Role((int) $row['id'], $row['uid'], $row['name'], $row['slug'], $row['created_at'], $row['updated_at']);
    }
}
