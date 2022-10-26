<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestItem;

use Symfony\Component\HttpFoundation\Response;
use Test\Functional\AbstractGetFunctionalTest;

class GetTestItemTest extends AbstractGetFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_ITEM = '/api/test_items/%s.json';


    protected static function getUri(): string
    {
        return self::TEST_ITEM;
    }

    public function testItGetsTestItem()
    {
        $testItem = $this->createEntity();

        $response = $this->get(sprintf(self::TEST_ITEM, $testItem->getId()));
        $this->assertResponseCode(Response::HTTP_OK);

        self::assertEquals($testItem->getId()->getValue(), $response['id']);
        self::assertEquals($testItem->getDescription()->getValue(), $response['description']);
        self::assertEquals($testItem->getAmount()->getValue(), $response['amount']);
        self::assertEquals($testItem->getTestCollectionId()->getValue(), $response['testCollectionId']);
    }

    public function testItFailsToGetNonExistentTestItem()
    {
        $this->itFailsToGetNonExistent();
    }

    public function testItFailsToGetNonExistentTestItemByInvalidId()
    {
        $this->testItFailsToGetNonExistentByInvalidId();
    }
}
