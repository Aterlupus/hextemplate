<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestItem;

use App\Core\Util\TypeInspector;
use App\Core\Uuid;
use App\TestItem\Domain\TestItem;
use Symfony\Component\HttpFoundation\Response;
use Test\Functional\AbstractFunctionalTest;

class GetTestItemTest extends AbstractFunctionalTest
{
    private const TEST_ITEM = '/api/test_items/%s.json';


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
        //$id = 'xxx'; //TODO: Test for this format
        $id = Uuid::string();

        $response = $this->get(sprintf(self::TEST_ITEM, $id));
        $this->assertResponseCode(Response::HTTP_NOT_FOUND);

        self::assertEquals(sprintf('Resource %s of id "%s" not found', TypeInspector::getClassName(TestItem::class), $id), $response);
    }
}
