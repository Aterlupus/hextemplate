<?php
declare(strict_types=1);

namespace Test\Helper;

use Doctrine\ORM\EntityManagerInterface;

//TODO: Consider removal - since class has been cleared of dependencies of specific Entities
class EntityGenerator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function saveEntity(object $object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
