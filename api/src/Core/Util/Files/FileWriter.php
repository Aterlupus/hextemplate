<?php
declare(strict_types=1);

namespace App\Core\Util\Files;

use InvalidArgumentException;

class FileWriter
{
    public static function createWritableFile(string $filePath): mixed
    {
        $result = fopen($filePath, 'w');

        if (false !== $result) {
            return $result;
        } else {
            throw new InvalidArgumentException(sprintf('Couldn\'t create file "%s"', $filePath));
        }
    }

    public static function closeFile($filePointer): void
    {
        if (false === fclose($filePointer)) {
            throw new \InvalidArgumentException('Couldn\'t close file');
        }
    }

    public static function assureDirectoryExists(string $path): void
    {
        if (false === file_exists($path)) {
            self::createDirectory($path);
        }
    }

    public static function createDirectory(string $path): void
    {
        if (false === mkdir($path, 0777, true)) {
            throw new InvalidArgumentException(sprintf('Couldn\'t create directory "%s"', $path));
        }
    }

    public static function removeEmptyDirectory(string $path): void
    {
        if (false === rmdir($path)) {
            throw new InvalidArgumentException(sprintf('Couldn\'t remove directory "%s"', $path));
        }
    }
}
