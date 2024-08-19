<?php

declare(strict_types = 1);

namespace Mega\Entity;

use DateTime;

class File extends AbstractEntity
{
    public function __construct(
        protected ?int $id,
        protected string $uid,
        protected ?User $user,
        protected string $name,
        protected string $contentType,
        protected string $data,
        protected int $size,
        protected ?DateTime $createdAt,
        protected ?DateTime $updatedAt
    ) {
        parent::__construct($id, $createdAt, $updatedAt);
    }

    public function toArray(): array
    {
        $data = [
            'uid' => $this->uid,
            'contentType' => $this->contentType,
            'name' => $this->name,
            'size' => $this->size,
            'links' => [
                [
                    'type' => 'metadata',
                    'link' => sprintf('/file/%s', $this->uid),
                ],
                [
                    'type' => 'download',
                    'link' => sprintf('/file/%s?download=true', $this->uid),
                ],
            ],
        ];

        if ($this->user) {
            $data['user'] = $this->user->toArray();
        }

        return array_merge(parent::toArray(), $data);
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
