<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use Test\Functional\Shared\AbstractPostFunctionalTest;

class PostTestItemTest extends AbstractPostFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_ITEM = '/api/test_items.json';


    protected static function getUri(): string
    {
        return self::TEST_ITEM;
    }

    public function testItCreatesTestItem()
    {
        $this->itCreates();
    }

    public function testItFailsOnCreatingTestItemWithoutDescription()
    {
        $this->itFailsOnCreatingWithoutFieldValue('description');
    }

    public function testItFailsOnCreatingTestItemWithEmptyDescription()
    {
        $this->itFailsOnCreatingWithTooShortFieldValue('description', 3);
    }

    public function testItFailsOnCreatingTestItemWithTooLongDescription()
    {
        $this->itFailsOnCreatingWithTooLongFieldValue('description', 1024);
    }
}
