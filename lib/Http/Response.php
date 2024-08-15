<?php

declare(strict_types = 1);

namespace Lib\Http;

class Response
{
    protected string $content = '';
    protected int $statusCode = self::HTTP_STATUS_OK;
    protected array $headers = [];

    public const HTTP_STATUS_NOT_FOUND = 404;
    public const HTTP_STATUS_OK = 200;

    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $header) {
            header($header);
        }

        echo $this->content;
    }

    public function setContent($content): Response
    {
        $this->content = $content;

        return $this;
    }

    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setHeaders(array $headers): Response
    {
        $this->headers = $headers;

        return $this;
    }
}
