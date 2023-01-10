<?php
declare(strict_types=1);

namespace App\Core\Exception\File;

class NoFileException extends \Exception
{
    public function __construct(string $filePath)
    {
        $message = sprintf('No file found in: %s', $filePath);
        parent::__construct($message);
    }
}

