<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterDoctrineCustomTypesCompilerPass implements CompilerPassInterface
{
    private const DOCTRINE_CUSTOM_TYPES_TAG = 'doctrine.custom_type';


    public function process(ContainerBuilder $container): void
    {
        $types = self::getTypes($container);
        DoctrineCustomTypesRegisterer::addTypes($types);
    }

    private function getTypes(ContainerBuilder $container): array
    {
        return array_keys(
            $container->findTaggedServiceIds(self::DOCTRINE_CUSTOM_TYPES_TAG)
        );
    }
}
