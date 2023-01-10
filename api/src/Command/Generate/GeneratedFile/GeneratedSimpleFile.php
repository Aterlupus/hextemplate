<?php
declare(strict_types=1);

namespace App\Command\Generate\GeneratedFile;

class GeneratedSimpleFile implements GeneratedFileInterface
{
    public function __construct(
        private readonly string $fileContent,
        private readonly string $namespace,
        private readonly string $filename,
        private readonly string $extension
    ) {}

    public function getFileContent(): string
    {
        return $this->fileContent;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getClassname(): string
    {
        return $this->filename;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }
}
