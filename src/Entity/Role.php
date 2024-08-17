<?php

declare(strict_types = 1);

namespace Mega\Entity;

use DateTime;

class Role extends AbstractEntity
{
    public const ROLE_ADMIN = 'role-admin';
    public const ROLE_USER = 'role-user';
    public const ROLE_ANONYMOUS = 'role-anonymous';

    public function __construct(
        protected ?int $id,
        protected string $uid,
        protected string $name,
        protected string $slug,
        protected DateTime $createdAt,
        protected DateTime $updatedAt
    ) {
        parent::__construct($id, $createdAt, $updatedAt);
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
