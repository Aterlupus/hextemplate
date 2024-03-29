<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use App\Core\Suit\HttpMethodsSuit;
use App\TestItem\Domain\TestItem;
use App\TestItem\Domain\TestItemIsActive;
use Symfony\Component\HttpFoundation\Response;
use Test\Functional\Shared\AbstractHttpFunctionalTest;

class PatchActivateTestItemTest extends AbstractHttpFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_ITEM = '/api/test_items/%s/activate.json';


    protected static function getHttpMethod(): string
    {
        return HttpMethodsSuit::PATCH;
    }

    public function testItActivatesTestItem()
    {
        $testItem = $this->createEntity(['isActive' => new TestItemIsActive(false)]);

        $uri = sprintf(self::TEST_ITEM, $testItem->getId());
        $response = $this->patch($uri, self::json());
        self::assertResponseCode(Response::HTTP_OK);

        /** @var TestItem $testItem */
        $testItem = $this->getEntity(self::getEntityClass(), $testItem->getId());

        self::assertResponseAndEntityIdentity($response, $testItem);
        self::assertTrue($testItem->getIsActive()->getValue());
    }

    //TODO: Test for missing TestItem
}
