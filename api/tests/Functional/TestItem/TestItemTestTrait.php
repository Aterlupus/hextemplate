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
}
