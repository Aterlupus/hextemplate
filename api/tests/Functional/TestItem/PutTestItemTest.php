<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use Test\Functional\Shared\AbstractPutFunctionalTest;

class PutTestItemTest extends AbstractPutFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_COLLECTION = '/api/test_items/%s.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    public function testItUpdatesTestItem()
    {
        $this->itUpdates();
    }

    public function testItFailsOnUpdatingTestItemWithoutDescription()
    {
        $this->itFailsOnUpdatingWithoutFieldValue('description');
    }

    public function testItFailsOnUpdatingTestItemWithEmptyDescription()
    {
        $this->itFailsOnUpdatingWithTooShortFieldValue('description', 3);
    }

    public function testItFailsOnUpdatingTestItemWithTooLongDescription()
    {
        $this->itFailsOnUpdatingWithTooLongFieldValue('description', 1024);
    }
}
