<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Config;

class DoctrineMappingsGenerator
{
    public static function getMappings(): array
    {
        $mappings = [];
        foreach (DomainResourcePathsProvider::getDomains() as $domain) {
            $mappings += [
                $domain => self::getDomainMapping($domain)
            ];
        }

        return $mappings;
    }

    private static function getDomainMapping(string $domain): array
    {
        return [
            'is_bundle' => false,
            'type' => 'xml',
            'dir' => sprintf('%%kernel.project_dir%%/src/%s/Infrastructure/Persistence/Doctrine/', $domain),
            'prefix' => sprintf('App\%s\Domain', $domain),
            'alias' => sprintf('App\%s\Domain', $domain),
        ];
    }
}
