<?php

declare(strict_types = 1);

namespace Mega\Entity;

use DateTime;

class UserAccess extends AbstractEntity
{
    public const TYPE_REFRESH = 'refresh';
    public const TYPE_ACCESS  = 'access';

    public function __construct(
        protected ?int $id,
        protected User $user,
        protected string $token,
        protected string $type,
        protected DateTime $expiresAt,
        protected ?DateTime $createdAt,
        protected ?DateTime $updatedAt
    ) {
        parent::__construct($id, $createdAt, $updatedAt);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getExpiresAt(): DateTime
    {
        return $this->expiresAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'type' => $this->type,
            'expires_at' => $this->expiresAt->format('Y-m-dTh:i:s'),
            'created_at' => $this->createdAt->format('Y-m-dTh:i:s'),
        ];
    }
}
