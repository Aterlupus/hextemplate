<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Shared\Config\DoctrineMappingsGenerator;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->extension('doctrine', [
        'orm' => [
            'mappings' => DoctrineMappingsGenerator::getMappings(),
        ]
    ]);
};
