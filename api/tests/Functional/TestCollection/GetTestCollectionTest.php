<?php
declare(strict_types=1);

namespace Test\Functional\TestCollection;

use Symfony\Component\HttpFoundation\Response;
use Test\Functional\Shared\AbstractGetFunctionalTest;

class GetTestCollectionTest extends AbstractGetFunctionalTest
{
    use TestCollectionTestTrait;

    private const TEST_COLLECTION = '/api/test_collections/%s.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    public function testItGetsTestCollection()
    {
        $testCollection = self::createEntity();

        $response = $this->get(sprintf(self::TEST_COLLECTION, $testCollection->getId()));
        $this->assertResponseCode(Response::HTTP_OK);

        self::assertEquals($testCollection->getId()->getValue(), $response['id']);
        self::assertEquals($testCollection->getName()->getValue(), $response['name']);
        self::assertSameSize($testCollection->getTestItemsIds()->getValues(), $response['testItemsIds']);
        foreach ($response['testItemsIds'] as $index => $testItemId) {
            self::assertEquals($testCollection->getTestItemsIds()->getValue()[$index]->getValue(), $testItemId);
        }
    }

    public function testItFailsToGetNonExistentTestCollection()
    {
        $this->itFailsToGetNonExistent();
    }

    public function testItFailsToGetNonExistentTestCollectionByInvalidId()
    {
        $this->testItFailsToGetNonExistentByInvalidId();
    }
}
