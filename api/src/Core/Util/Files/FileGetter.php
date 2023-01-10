<?php
declare(strict_types=1);

namespace App\Core\Util\Files;

class FileGetter
{
    public static function doesFileExist(string $path): bool
    {
        return file_exists($path);
    }
}
