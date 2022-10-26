<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestItem;

use App\Core\Uuid;
use App\TestItem\Domain\TestItem;
use Test\Functional\AbstractPostFunctionalTest;
use Test\Functional\RequestJson;
use Test\Helper\Random;

class PostTestItemTest extends AbstractPostFunctionalTest
{
    private const TEST_ITEM = '/api/test_items.json';


    protected static function getUri(): string
    {
        return self::TEST_ITEM;
    }

    protected static function getPostJson(): RequestJson
    {
        return self::json()
            ->set('description', Random::getString())
            ->set('amount', Random::getInteger(0, 1000))
            ->set('testCollectionId', Uuid::string());
    }

    public function testItCreatesTestItem()
    {
        $this->itCreates(TestItem::class);
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
