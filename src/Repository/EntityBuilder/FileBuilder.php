<?php

declare(strict_types = 1);

namespace Mega\Repository\EntityBuilder;

use Mega\Entity\File;

class FileBuilder extends AbstractEntityBuilder
{
    public function buildFromRow(array $row, string $prefix = '')
    {
        $row['id'] = (int) $row[$prefix . 'id'];
        $row['uid'] = $row[$prefix . 'uid'];
        $row['name'] = $row[$prefix . 'name'];
        $row['content_type'] = $row[$prefix . 'content_type'];
        $row['size'] = $row[$prefix . 'size'];
        $row['file_data'] = $row[$prefix . 'file_data'];
        $row['created_at'] = $row[$prefix . 'created_at'] ?? null;
        $row['updated_at'] = $row[$prefix . 'updated_at'] ?? null;

        $this->transformStringDateToDatetime($row);

        return new File($row['id'], $row['uid'], null, $row['name'], $row['content_type'], $row['file_data'], $row['size'], $row['created_at'], $row['updated_at']);
    }
}
