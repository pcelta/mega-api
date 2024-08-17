<?php

declare(strict_types = 1);

namespace Mega\Repository\EntityBuilder;

interface EntityBuilderInterface
{
    public function buildFromRow(array $row, string $prefix = '');
}
