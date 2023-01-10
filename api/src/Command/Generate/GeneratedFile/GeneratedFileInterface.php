<?php
declare(strict_types=1);

namespace App\Command\Generate\GeneratedFile;

interface GeneratedFileInterface
{
    public function getNamespace(): string;

    public function getClassname(): string;

    public function getExtension(): string;
}
