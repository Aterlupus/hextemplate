<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator;

use App\Command\Generate\GeneratedFile\GeneratedFileInterface;

interface FileGeneratorInterface
{
    public function getFile(): GeneratedFileInterface;
}
