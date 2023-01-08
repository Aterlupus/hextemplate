<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use Test\Functional\Shared\AbstractPutFunctionalTest;
use Test\Functional\Shared\RequestJson;
use Test\Helper\Random;

class PutUpdateTestItemTest extends AbstractPutFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_COLLECTION = '/api/test_items/%s.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    protected function getEntityJson(): RequestJson
    {
        $testCollection = $this->eg->getTestCollection();

        return self::json()
            ->set('description', Random::getString())
            ->set('amount', Random::getInteger(0, 1000))
            ->set('testCollectionId', $testCollection->getId()->getValue());
    }

    public function testItUpdatesTestItem()
    {
        $this->itUpdates();
    }

    public function testItFailsOnUpdatingTestItemWithoutDescription()
    {
        $this->itFailsOnUpdatingWithoutFieldValue('description');
    }

    public function testItFailsOnUpdatingTestItemWithTooShortDescription()
    {
        $this->itFailsOnUpdatingWithTooShortFieldValue('description', 3);
    }

    public function testItFailsOnUpdatingTestItemWithTooLongDescription()
    {
        $this->itFailsOnUpdatingWithTooLongFieldValue('description', 1024);
    }

    public function testItFailsOnUpdatingTestItemWithoutAmount()
    {
        $this->itFailsOnUpdatingWithoutFieldValue('amount');
    }

    public function testItFailsOnUpdatingTestItemWithoutTestCollectionId()
    {
        $this->itFailsOnUpdatingWithoutFieldValue('testCollectionId');
    }

    //TODO: Test for missing TestItem
}
