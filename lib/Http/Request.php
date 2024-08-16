<?php

declare(strict_types = 1);

namespace Lib\Http;

class Request
{
    private string $body;

    public function __construct(protected array $server, protected array $requestParams, protected array $getParams, protected array $postParams)
    {
        $this->body = file_get_contents('php://input');
    }

    public function getBody(): ?array
    {
        return json_decode($this->body, true);
    }

    public function getParam(string $param, $default = null)
    {
        if (isset($this->getParams[$param])) {
            return $this->cleanUpInputData($this->getParams[$param]);
        }

        if (isset($this->postParams[$param])) {
            return $this->cleanUpInputData($this->postParams[$param]);
        }

        return $default;
    }

    private function cleanUpInputData(string $inputData): string
    {
        return htmlspecialchars($inputData, ENT_QUOTES, 'UTF-8');
    }
}
