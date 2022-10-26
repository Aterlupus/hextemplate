<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineCustomTypesRegistererBundle extends Bundle
{
    public function boot(): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        DoctrineCustomTypesRegisterer::register($entityManager);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new RegisterDoctrineCustomTypesCompilerPass);
    }
}
