<?php

declare(strict_types = 1);

namespace Lib\Http;

use Lib\Http\UploadedFile;

class Request
{
    private string $body;
    private array $customParams = [];

    public function __construct(protected array $server, protected array $requestParams, protected array $getParams, protected array $postParams)
    {
        $this->body = file_get_contents('php://input');
    }

    public function getBody(): ?array
    {
        return json_decode($this->body, true);
    }

    public function getContent()
    {
        return $this->body;
    }

    public function getParam(string $param, $default = null)
    {
        if (isset($this->getParams[$param])) {
            return $this->cleanUpInputData($this->getParams[$param]);
        }

        if (isset($this->postParams[$param])) {
            return $this->cleanUpInputData($this->postParams[$param]);
        }

        if (isset($this->customParams[$param])) {
            return $this->cleanUpInputData($this->customParams[$param]);
        }

        return $default;
    }

    private function cleanUpInputData(string $inputData): string
    {
        return htmlspecialchars($inputData, ENT_QUOTES, 'UTF-8');
    }

    public function setCustomParam(string $name, $value): void
    {
        $this->customParams[$name] = $value;
    }

    public function getAuthorizationToken(): string
    {
        $header = $this->server['HTTP_AUTHORIZATION'] ?? '';

        return str_replace('Bearer: ', '', $header);
    }

    public function getContentType(): string
    {
        return $this->server['HTTP_CONTENT_TYPE'] ?? '';
    }

    public function getUploadedFile(): UploadedFile
    {
        return new UploadedFile($_FILES);
    }
}
