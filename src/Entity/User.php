<?php

declare(strict_types = 1);

namespace Mega\Entity;

use DateTime;

class User extends AbstractEntity
{
    public const PASSWORD_CLEAN_STATE = 'clean-password-for-security-reasons';
    protected array $roles = [];

    public function __construct(
        protected ?int $id,
        protected string $uid,
        protected string $username,
        protected string $password,
        protected bool $isActive,
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
            'is_active' => $this->isActive,
        ];

        if ($this->getRoles()) {
            foreach ($this->getRoles() as $role) {
                $data['roles'][] = $role->toArray();
            }
        }

        return array_merge(parent::toArray(), $data);
    }


    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
