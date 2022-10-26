<?php
declare(strict_types=1);

namespace App\TestCollection\Infrastructure\Persistence\Repository;

use App\Shared\Infrastructure\Persistence\Repository\AbstractDoctrineRepository;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionRepositoryInterface;

class TestCollectionDoctrineRepository extends AbstractDoctrineRepository implements TestCollectionRepositoryInterface
{
    protected function getEntityClass(): string
    {
        return TestCollection::class;
    }

    public function get(TestCollectionId $testCollectionId): ?TestCollection
    {
        return $this->getRepository()->find($testCollectionId);
    }

    public function save(TestCollection $testCollection): void
    {
        $this->saveEntity($testCollection);
    }
}
