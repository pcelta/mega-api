<?php

declare(strict_types = 1);

namespace Mega\Repository\EntityBuilder;

use Mega\Entity\UserAccess;

class UserAccessBuilder extends AbstractEntityBuilder
{
    public function buildFromRow(array $row, string $prefix = '')
    {
        $row['id'] = $row[$prefix . 'id'];
        $row['token'] = $row[$prefix . 'token'];
        $row['type'] = $row[$prefix . 'type'];
        $row['expires_at'] = $row[$prefix . 'expires_at'];
        $row['created_at'] = $row[$prefix . 'created_at'];
        $row['updated_at'] = $row[$prefix . 'updated_at'];

        $this->transformStringDateToDatetime($row);

        return new UserAccess((int) $row['id'], null, $row['token'], $row['type'], $row['expires_at'], $row['created_at'], $row['updated_at']);
    }
}
