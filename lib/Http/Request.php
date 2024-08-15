<?php

declare(strict_types = 1);

namespace Lib\Http;

class Request
{
    public function __construct(protected array $server, protected array $get, protected array $post)
    {

    }
}
