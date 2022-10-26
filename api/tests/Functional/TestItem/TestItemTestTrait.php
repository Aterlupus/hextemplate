<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use App\Core\Uuid;
use App\TestItem\Domain\TestItem;
use Test\Functional\Shared\RequestJson;
use Test\Helper\Random;

trait TestItemTestTrait
{
    protected static function getEntityClass(): string
    {
        return TestItem::class;
    }

    protected static function getEntityJson(): RequestJson
    {
        return self::json()
            ->set('description', Random::getString())
            ->set('amount', Random::getInteger(0, 1000))
            ->set('testCollectionId', Uuid::string());
    }

    protected function createEntity(): TestItem
    {
        //TODO: eg?
        return $this->eg->getTestItem();
    }
}
