<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use Symfony\Component\HttpFoundation\Response;
use Test\Functional\Shared\AbstractFunctionalTest;

class DeleteTestItemTest extends AbstractFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_ITEM = '/api/test_items/%s.json';


    public function testItDeletesTestItem()
    {
        $testItem = $this->createEntity();

        $this->delete(sprintf(self::TEST_ITEM, $testItem->getId()));
        self::assertResponseCode(Response::HTTP_NO_CONTENT);

        $foundTestItem = $this->findEntity(self::getEntityClass(), $testItem->getId()->getValue());
        self::assertNull($foundTestItem);
    }
}
