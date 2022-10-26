<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestItem;

use App\TestItem\Domain\TestItem;
use Symfony\Component\HttpFoundation\Response;
use Test\Functional\AbstractGetFunctionalTest;

class GetTestItemTest extends AbstractGetFunctionalTest
{
    private const TEST_ITEM = '/api/test_items/%s.json';


    protected static function getUri(): string
    {
        return self::TEST_ITEM;
    }

    public function testItGetsTestItem()
    {
        $testItem = $this->eg->getTestItem();
        $testItemId = $testItem->getId()->getValue();

        $response = $this->get(sprintf(self::TEST_ITEM, $testItemId));
        $this->assertResponseCode(Response::HTTP_OK);

        self::assertEquals($testItem->getId()->getValue(), $response['id']);
        self::assertEquals($testItem->getDescription()->getValue(), $response['description']);
        self::assertEquals($testItem->getAmount()->getValue(), $response['amount']);
        self::assertEquals($testItem->getTestCollectionId()->getValue(), $response['testCollectionId']);
    }

    public function testItFailsToGetNonExistentTestItem()
    {
        $this->itFailsToGetNonExistent(TestItem::class);
    }

    public function testItFailsToGetNonExistentTestItemByInvalidId()
    {
        $this->testItFailsToGetNonExistentByInvalidId();
    }
}
