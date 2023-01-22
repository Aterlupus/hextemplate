<?php
declare(strict_types=1);

namespace Test\Functional\TestCollection;

use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionTestItemsIds;
use App\TestItem\Domain\TestItemId;
use Test\Functional\Shared\RequestJson;
use Test\Functional\TestItem\TestItemCreatorTrait;
use Test\Helper\Random;

trait TestCollectionTestTrait
{
    use TestCollectionCreatorTrait, TestItemCreatorTrait;


    protected static function getEntityClass(): string
    {
        return TestCollection::class;
    }

    protected function getEntityJson(): RequestJson
    {
        return self::json()
            ->set('name', Random::getString())
            ->set('testItemsIds', []);
    }

    protected function createEntity(): TestCollection
    {
        $testItemsUuids = Random::getUuids(4);
        $testItemsIds = self::getTestItemsIdsFromUuids($testItemsUuids);
        $testCollection = $this->getTestCollection(['testItemsIds' => $testItemsIds]);
        $this->getTestItemsFromTestItemsIds($testCollection, $testItemsIds);

        return $testCollection;
    }

    /*
     * Helpers
     */

    private static function getTestItemsIdsFromUuids(array $testItemsUuids): TestCollectionTestItemsIds
    {
        return new TestCollectionTestItemsIds(
            array_map(
                fn(string $uuid) => new TestItemId($uuid),
                $testItemsUuids
            )
        );
    }

    private function getTestItemsFromTestItemsIds(TestCollection $testCollection, TestCollectionTestItemsIds $testItemsIds): array
    {
        return array_map(
            fn(TestItemId $testItemId) => $this->getTestItem([
                'id' => $testItemId,
                'testCollection' => $testCollection
            ]),
            $testItemsIds->getValue()
        );
    }
}
