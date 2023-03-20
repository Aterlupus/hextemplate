<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Shared\Infrastructure\Config\DomainResourcePathsProvider;

return static function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->extension('api_platform', [
        'mapping' => [
            'paths' => DomainResourcePathsProvider::getPaths()
        ]
    ]);
};
