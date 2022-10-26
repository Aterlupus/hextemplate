<?php
declare(strict_types=1);

namespace Test\Helper;

use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionId;
use App\TestCollection\Domain\TestCollectionName;
use App\TestCollection\Domain\TestCollectionTestItemsIds;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemAmount;
use App\TestItem\Domain\TestItemDescription;
use App\TestItem\Domain\TestItemId;
use Doctrine\ORM\EntityManagerInterface;

class EntityGenerator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function getTestItem(array $values = []): TestItem
    {
        //TODO: Delegate to Factory
        $testItem = new TestItem(
            $values['id'] ?? TestItemId::new(),
            new TestItemDescription(Random::getString(200)),
            new TestItemAmount(Random::getPositiveInteger(500)),
            isset($values['testCollection']) ? $values['testCollection']->getId() : $this->getTestCollection()->getId(),
        );

        $this->saveEntity($testItem);

        return $testItem;
    }

    public function getTestCollection(array $values = []): TestCollection
    {
        //TODO: Delegate to Factory
        $testCollection = new TestCollection(
            TestCollectionId::new(),
            new TestCollectionName(Random::getString(16)),
            $values['testItemsIds'] ?? new TestCollectionTestItemsIds([]),
        );

        $this->saveEntity($testCollection);

        return $testCollection;
    }

    private function saveEntity(object $object): void
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
