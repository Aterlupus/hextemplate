<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer;

use App\Core\Util\Files\FileGetter;
use App\Core\Util\Regex;

class DoctrineCustomTypesProvider
{
    //TODO: Find more time optimal way to get custom types
    public static function getTypes(): array
    {
        $types = [];
        foreach (self::getCustomTypesFilePaths() as $type) {
            [$domain, $class] = Regex::getRegexMatch($type, '#([^/]*?)/Infrastructure/Persistence/Doctrine/([^/]*Type)\.php#');
            if ('Shared' !== $domain) {
                $types[] = new (self::getTypeClass($domain, $class))();
            }
        }

        return $types;
    }

    private static function getCustomTypesFilePaths(): array
    {
        return array_filter(
            FileGetter::getFilesPaths('src'),
            fn(string $filepath) => 0 < preg_match('#(.*?)/Infrastructure/Persistence/Doctrine/([^/]*Type)\.php#', $filepath)
        );
    }

    private static function getTypeClass(string $domain, string $class): string
    {
        return sprintf('App\%s\Infrastructure\Persistence\Doctrine\%s', $domain, $class);
    }
}
