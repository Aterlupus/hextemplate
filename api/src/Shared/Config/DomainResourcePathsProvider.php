<?php
declare(strict_types=1);

namespace App\Shared\Config;

class DomainResourcePathsProvider
{
    private const DOMAIN_RESOURCE_PATH_FORMAT = '%%kernel.project_dir%%/src/%s/Infrastructure/ApiPlatform';

    private const NON_DOMAIN_DIRECTORIES = [
        '.',
        '..',
        'Command',
        'Core',
        'Shared',
        'Kernel.php',
    ];

    public static function getPaths(): array
    {
        return array_map(
            fn($domain) => sprintf(self::DOMAIN_RESOURCE_PATH_FORMAT, $domain),
            self::getDomains()
        );
    }

    public static function getDomains(): array
    {
        return array_diff(
            self::getSrcFiles(),
            self::NON_DOMAIN_DIRECTORIES
        );
    }

    private static function getSrcFiles(): array
    {
        return scandir(
            SourceDirectoryFinder::getSrcDir()
        );
    }
}
