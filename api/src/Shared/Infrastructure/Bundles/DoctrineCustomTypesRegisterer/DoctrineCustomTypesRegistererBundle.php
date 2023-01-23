<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer;

use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineCustomTypesRegistererBundle extends Bundle
{
    /**
     * @throws DoctrineException
     */
    public function boot(): void
    {
        foreach (DoctrineCustomTypesProvider::getTypes() as $typeObject) {
            self::registerType($typeObject);
        }
    }

    /**
     * @throws DoctrineException
     */
    private function registerType(DoctrineCustomTypeInterface $type): void
    {
        if (false === Type::hasType($type->getName())) {
            Type::addType($type->getName(), $type::class);
            $this->getEntityManager()->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping($type::class, $type->getName());
        }
    }

    private function getEntityManager(): EntityManagerInterface
    {
        static $entityManager;
        if (false === isset($entityManager)) {
            $entityManager = $this->container->get('doctrine.orm.entity_manager');
        }

        return $entityManager;
    }
}
