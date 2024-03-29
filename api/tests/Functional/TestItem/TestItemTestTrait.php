<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use App\TestItem\Domain\TestItem;
use Test\Functional\Shared\RequestJson;
use Test\Helper\Random;

trait TestItemTestTrait
{
    use TestItemCreatorTrait;


    protected static function getEntityClass(): string
    {
        return TestItem::class;
    }

    protected function getEntityJson(): RequestJson
    {
        $testCollection = $this->getTestCollection();

        return self::json()
            ->set('description', Random::getString())
            ->set('amount', Random::getInteger(0, 1000))
            ->set('comment', Random::getString(32))
            ->set('testCollectionId', $testCollection->getId()->getValue());
    }

    //TODO: bad sign, maybe whole thing should be replaced by some "expectation" classes?
    protected static function getDefaultFieldsValues(): array
    {
        return [
            'isActive' => true
        ];
    }

    protected function createEntity(array $values = []): TestItem
    {
        return $this->getTestItem($values);
    }

    protected static function assertTestItemResponseIdentity(TestItem $testItem, array $testItemResponse): void
    {
        self::assertEquals($testItem->getId()->getValue(), $testItemResponse['id']);
        self::assertEquals($testItem->getDescription()->getValue(), $testItemResponse['description']);
        self::assertEquals($testItem->getAmount()->getValue(), $testItemResponse['amount']);
        self::assertEquals($testItem->getIsActive()->getValue(), $testItemResponse['isActive']);
        self::assertEquals($testItem->getComment()->getValue(), $testItemResponse['comment']);
        self::assertEquals($testItem->getTestCollectionId()->getValue(), $testItemResponse['testCollectionId']);
    }

    protected static function assertTestItemsResponseIdentity(array $testItems, array $testItemsResponse): void
    {
        foreach ($testItems as $index => $testItem) {
            self::assertTestItemResponseIdentity($testItem, $testItemsResponse[$index]);
        }
    }
}
