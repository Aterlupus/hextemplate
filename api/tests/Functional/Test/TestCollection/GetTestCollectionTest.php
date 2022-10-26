<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestCollection;

use App\Core\Util\TypeInspector;
use App\Core\Uuid;
use App\TestCollection\Domain\TestCollection;
use App\TestCollection\Domain\TestCollectionTestItemsIds;
use App\TestItem\Domain\TestItemId;
use Symfony\Component\HttpFoundation\Response;
use Test\Functional\AbstractFunctionalTest;
use Test\Helper\Random;

class GetTestCollectionTest extends AbstractFunctionalTest
{
    private const TEST_COLLECTION = '/api/test_collections/%s.json';


    public function testItGetsTestCollection()
    {
        $testItemsUuids = Random::getUuids(4);
        $testItemsIds = self::getTestItemsIdsFromUuids($testItemsUuids);
        $testCollection = $this->eg->getTestCollection(['testItemsIds' => $testItemsIds]);
        $testItems = $this->getTestItemsFromTestItemsIds($testCollection, $testItemsIds);
        $testCollectionId = $testCollection->getId()->getValue();

        $response = $this->get(sprintf(self::TEST_COLLECTION, $testCollectionId));
        $this->assertResponseCode(Response::HTTP_OK);

        self::assertEquals($testCollection->getId()->getValue(), $response['id']);
        self::assertEquals($testCollection->getName()->getValue(), $response['name']);
        self::assertSameSize($testItemsUuids, $response['testItemsIds']);
        foreach ($response['testItemsIds'] as $index => $testItemId) {
            self::assertEquals($testCollection->getTestItemsIds()->getValue()[$index]->getValue(), $testItemId);
            self::assertEquals($testItems[$index]->getId()->getValue(), $testItemId);
        }
    }

    public function testItFailsToGetNonExistentTestCollection()
    {
        $id = Uuid::string();

        $response = $this->get(sprintf(self::TEST_COLLECTION, $id));
        $this->assertResponseCode(Response::HTTP_NOT_FOUND);

        self::assertEquals(sprintf('Resource %s of id "%s" not found', TypeInspector::getClassName(TestCollection::class), $id), $response);
    }

    public function testItFailsToGetNonExistentTestCollectionByInvalidId()
    {
        $id = 'xxx';

        $response = $this->get(sprintf(self::TEST_COLLECTION, $id));
        $this->assertResponseCode(Response::HTTP_NOT_FOUND);

        self::assertEquals('An error occurred', $response['title']);
        self::assertEquals('Invalid identifier value or configuration.', $response['detail']);
    }

    /*
     * Helpers
     */

    private static function getTestItemsIdsFromUuids(array $testItemsUuids): TestCollectionTestItemsIds
    {
        return new TestCollectionTestItemsIds(
            array_map(
                fn(string $uuid) => new TestItemId($uuid),
                $testItemsUuids
            )
        );
    }

    private function getTestItemsFromTestItemsIds(TestCollection $testCollection, TestCollectionTestItemsIds $testItemsIds): array
    {
        return array_map(
            fn(TestItemId $testItemId) => $this->eg->getTestItem([
                'id' => $testItemId,
                'testCollection' => $testCollection
            ]),
            $testItemsIds->getValue()
        );
    }
}