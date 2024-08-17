<?php

declare(strict_types = 1);

namespace Mega\Repository\EntityBuilder;

use Mega\Entity\User;

class UserBuilder extends AbstractEntityBuilder
{
    public function buildFromRow(array $row, string $prefix = '')
    {
        $row['id'] = $row[$prefix . 'id'];
        $row['uid'] = $row[$prefix . 'uid'];
        $row['username'] = $row[$prefix . 'username'];
        $row['password'] = $row[$prefix . 'password'];
        $row['created_at'] = $row[$prefix . 'created_at'];
        $row['updated_at'] = $row[$prefix . 'updated_at'];

        $this->transformStringDateToDatetime($row);

        return new User((int) $row['id'], $row['uid'], $row['username'], $row['password'], $row['created_at'], $row['updated_at']);
    }
}
