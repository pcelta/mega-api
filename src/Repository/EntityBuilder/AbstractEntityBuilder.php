<?php

declare(strict_types = 1);

namespace Mega\Repository\EntityBuilder;

use DateTime;

abstract class AbstractEntityBuilder implements EntityBuilderInterface
{
    protected function transformStringDateToDatetime(array &$row): void
    {
        if (isset($row['created_at'])) {
            $row['created_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']);
        }

        if (isset($row['updated_at'])) {
            $row['updated_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['updated_at']);
        }

        if (isset($row['expires_at'])) {
            $row['expires_at'] = DateTime::createFromFormat('Y-m-d H:i:s', $row['expires_at']);
        }
    }
}
