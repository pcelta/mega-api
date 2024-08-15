<?php

declare(strict_types = 1);

namespace Lib\Http;

class JsonResponse extends Response
{
    private string $defaultHeader = 'Content-Type: application/json';
    protected array $data = [];

    public function send(): void
    {
        $this->headers[] = $this->defaultHeader;
        $this->content = json_encode($this->data);

        parent::send();
    }

    public function setData(array $data): JsonResponse
    {
        $this->data = $data;

        return $this;
    }
}
