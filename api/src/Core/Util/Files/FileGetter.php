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

    public static function getFilesPaths(string $path): array
    {
        return self::scanDir($path);
    }

    private static function scanDir($dir): array
    {
        $result = [];
        foreach (scandir($dir) as $filename) {
            if ($filename[0] !== '.') {
                $filePath = $dir . '/' . $filename;
                if (is_dir($filePath)) {
                    foreach (self::scanDir($filePath) as $childFilename) {
                        $result[] = $filename . '/' . $childFilename;
                    }
                } else {
                    $result[] = $filename;
                }
            }
        }

        return $result;
    }
}
