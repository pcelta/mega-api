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
        protected ?DateTime $createAt = null,
        protected ?DateTime $updatedAt = null
    ) {
        parent::__construct($id, $createAt, $updatedAt);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function toArray(): array
    {
        $data = [
            'uid' => $this->uid,
            'username' => $this->username,
        ];

        return array_merge(parent::toArray(), $data);
    }
}
