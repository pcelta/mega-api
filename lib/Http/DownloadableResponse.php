<?php

declare(strict_types = 1);

namespace Lib\Http;

use Mega\Entity\File;

class DownloadableResponse extends Response
{
    private string $defaultHeader = 'Content-Description: File Transfer';

    public function __construct(private File $file) {}

    public function send(): void
    {
        $this->headers[] = $this->defaultHeader;
        $this->headers[] = sprintf('Content-Type: %s', $this->file->getContentType()); // 'Content-Disposition: attachment; filename="' . $fileName . '"'
        $this->headers[] = sprintf('Content-Disposition: attachment; filename="%s"', $this->file->getName()); //header('Content-Length: ' . filesize($filePath));
        $this->headers[] = sprintf('Content-Length: %s', strlen($this->file->getData()));

        $this->content = $this->file->getData();

        parent::send();
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
