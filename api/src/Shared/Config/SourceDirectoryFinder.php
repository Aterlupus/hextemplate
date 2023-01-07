<?php
declare(strict_types=1);

namespace App\Shared\Config;

use RuntimeException;

//TODO: Somehow replace this travesty (created to resolve the disparity in 'current directory' dependent on caller)
class SourceDirectoryFinder
{
    private const POSSIBLE_PATHS = [
        '../src',
        './src',
    ];

    public static function getSrcDir(): string
    {
        foreach (self::POSSIBLE_PATHS as $srcPath) {
            if (file_exists($srcPath)) {
                return $srcPath;
            }
        }

        throw new RuntimeException('src directory not found');
    }
}
