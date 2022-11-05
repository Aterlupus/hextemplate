<?php
declare(strict_types=1);

namespace Test\Functional\TestCollection;

use Symfony\Component\HttpFoundation\Response;
use Test\Functional\Shared\AbstractFunctionalTest;

class DeleteTestCollectionTest extends AbstractFunctionalTest
{
    use TestCollectionTestTrait;

    private const TEST_COLLECTION = '/api/test_collections/%s.json';


    public function testItDeletesTestCollection()
    {
        $testCollection = $this->createEntity();

        $this->delete(sprintf(self::TEST_COLLECTION, $testCollection->getId()));
        self::assertResponseCode(Response::HTTP_NO_CONTENT);

        $foundTestCollection = $this->findEntity(self::getEntityClass(), $testCollection->getId()->getValue());
        self::assertNull($foundTestCollection);
    }
}
