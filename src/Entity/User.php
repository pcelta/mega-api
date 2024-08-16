<?php

declare(strict_types = 1);

namespace Mega\Entity;

use DateTime;

class User extends AbstractEntity
{
    public function __construct(
        protected ?int $id,
        protected string $uid,
        protected string $username,
        protected string $password,
        protected DateTime $createAt,
        protected DateTime $updatedAt
    ) {
        parent::__construct($id, $createAt, $updatedAt);
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
