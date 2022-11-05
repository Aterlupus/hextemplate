<?php
declare(strict_types=1);

namespace App\TestItem\Infrastructure\Persistence\Repository;

use App\Shared\Infrastructure\Persistence\Repository\AbstractDoctrineRepository;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemId;
use App\TestItem\Domain\TestItemRepositoryInterface;

class TestItemDoctrineRepository extends AbstractDoctrineRepository implements TestItemRepositoryInterface
{
    protected function getEntityClass(): string
    {
        return TestItem::class;
    }

    public function get(TestItemId $testItemId): ?TestItem
    {
        return $this->getRepository()->find($testItemId);
    }

    public function save(TestItem $testItem): void
    {
        $this->saveEntity($testItem);
    }

    public function delete(TestItem $testItem): void
    {
        $this->deleteEntity($testItem);
    }
}
