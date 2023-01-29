<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use App\TestItem\Domain\TestItem;
use Symfony\Component\HttpFoundation\Response;
use Test\Functional\Shared\AbstractFunctionalTest;

class GetTestItemsTest extends AbstractFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_ITEMS = '/api/test_items.json';


    protected static function getUri(): string
    {
        return self::TEST_ITEMS;
    }

    public function testItGetsAllTestItems()
    {
        $testItems = [
            $this->createEntity(),
            $this->createEntity(),
            $this->createEntity(),
        ];

        $response = $this->get(self::TEST_ITEMS);
        $this->assertResponseCode(Response::HTTP_OK);
        self::assertCount(3, $response);
        self::assertTestItemsResponseIdentity($testItems, $response);
    }

    public function testItGetsTestItems()
    {
        $testItems = [
            $this->createEntity(),
            $this->createEntity(),
            $this->createEntity(),
            $this->createEntity(),
            $this->createEntity(),
        ];

        $testItemsCases = [
            [$testItems[0], $testItems[2]],
            [$testItems[1], $testItems[3]],
            [$testItems[0], $testItems[1], $testItems[3], $testItems[4]],
        ];

        foreach ($testItemsCases as $testItemsCase) {
            $response = $this->get(self::TEST_ITEMS . self::getTestItemsQuery($testItemsCase));
            $this->assertResponseCode(Response::HTTP_OK);
            self::assertCount(count($testItemsCase), $response);
            self::assertTestItemsResponseIdentity($testItemsCase, $response);
        }
    }

    /*
     * Helpers
     */

    private static function getTestItemsQuery(array $testItems): string
    {
        return self::queryParams([
            'id' => array_map(
                fn(TestItem $testItem) => $testItem->getId()->getValue(),
                $testItems
            )
        ]);
    }

    private static function queryParams(array $parameters): string
    {
        return sprintf('?%s', http_build_query($parameters));
    }
}
