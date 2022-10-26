<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractDoctrineRepository
{
    abstract protected function getEntityClass(): string;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    protected function saveEntity(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

    protected function deleteEntity(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush($entity);
    }

    protected function getRepository(): ObjectRepository
    {
        return $this->getEntityManager()->getRepository(static::getEntityClass());
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }
}
