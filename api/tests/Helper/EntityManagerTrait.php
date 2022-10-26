<?php
declare(strict_types=1);

namespace Test\Helper;

use App\Core\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

trait EntityManagerTrait
{
    abstract protected function getEntityManager(): EntityManagerInterface;

    protected function getRepository(string $entityClass): ObjectRepository
    {
        return $this->getEntityManager()->getRepository($entityClass);
    }

    protected function findEntity(
        string $entityClass,
        array|Uuid|string $searchCriteria,
        array $orderBy = null
    ): ?object {
        $searchCriteria = $this->getPreparedCriteria($searchCriteria);
        /** @var EntityRepository $entityRepository */
        $entityRepository = $this->getRepository($entityClass);
        return $entityRepository->findOneBy($searchCriteria, $orderBy);
    }

    protected function getEntity(
        string $entityClass,
        array|Uuid|string $searchCriteria = [],
        array $orderBy = null
    ): object {
        $entity = $this->findEntity($entityClass, $searchCriteria, $orderBy);
        $this->validateEntity($entity, $entityClass);

        return $entity;
    }

    protected function getEntities(
        string $entityClass,
        array|Uuid|string $searchCriteria = [],
        array $orderBy = null
    ): array {
        $searchCriteria = $this->getPreparedCriteria($searchCriteria);
        return $this->getRepository($entityClass)->findBy($searchCriteria, $orderBy);
    }

    protected function validateEntity(
        ?object $entity,
        string $entityClass
    ): void {
        Assert::isInstanceOf($entity, $entityClass);
    }

    private static function getPreparedCriteria(array|Uuid|string $criteria): array
    {
        if (is_array($criteria)) {
            return $criteria;
        } elseif (is_string($criteria) || is_a($criteria, Uuid::class)) {
            return ['id' => $criteria];
        } else {
            throw new InvalidArgumentException(sprintf('Invalid search criteria: %s', $criteria));
        }
    }
}
