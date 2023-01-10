<?php
declare(strict_types=1);

namespace App\Core\Util\Files;

use App\Core\Exception\File\NoFileException;

class FileGetter
{
    /**
     * @throws NoFileException
     */
    public static function getContents(string $path): string
    {
        self::assertFileExists($path);

        return file_get_contents($path);
    }

    public static function doesFileExist(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @throws NoFileException
     */
    public static function assertFileExists(string $path): void
    {
        if (false === self::doesFileExist($path)) {
            throw new NoFileException($path);
        }
    }
}
