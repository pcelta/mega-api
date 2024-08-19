<?php

declare(strict_types = 1);

namespace Lib\Http;

use finfo;

class UploadedFile
{
    protected string $name;

    public function __construct(protected array $file) {}

    public function getFileSize(): int
    {
        return (int) $this->file['file']['size'];
    }

    public function getContentType(): string
    {
        $content = file_get_contents($this->file['file']['tmp_name']);
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        return $finfo->buffer($content);
    }

    public function getContent(): string
    {
        return file_get_contents($this->file['file']['tmp_name']);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
