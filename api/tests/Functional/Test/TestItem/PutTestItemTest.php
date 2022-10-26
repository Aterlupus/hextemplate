<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestItem;

use App\Core\Uuid;
use App\Shared\Domain\AbstractDomainEntity;
use App\TestItem\Domain\TestItem;
use Test\Functional\AbstractPutFunctionalTest;
use Test\Functional\RequestJson;
use Test\Helper\Random;

class PutTestItemTest extends AbstractPutFunctionalTest
{
    private const TEST_COLLECTION = '/api/test_items/%s.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    protected static function getEntityJson(): RequestJson
    {
        return self::json()
            ->set('description', Random::getString())
            ->set('amount', Random::getInteger(0, 1000))
            ->set('testCollectionId', Uuid::string());
    }

    protected function createEntityAndGetId(): string
    {
        $testItem = $this->eg->getTestItem();
        return $testItem->getId()->getValue();
    }

    protected static function assertRequestAndEntityIdentity(
        string $id,
        RequestJson $json,
        AbstractDomainEntity $domainEntity
    ): void {
        self::assertNotNull($domainEntity);
        self::assertEquals($id, $domainEntity->getId()->getValue());
        foreach ($json->getKeys() as $fieldName) {
            $getter = sprintf('get%s', $fieldName);
            self::assertEquals($json->get($fieldName), $domainEntity->$getter()->getValue());
        }
    }

    public function testItUpdatesTestItem()
    {
        $this->itUpdates(TestItem::class);
    }

    public function testItFailsOnUpdatingTestItemWithoutDescription()
    {
        $this->itFailsOnUpdatingWithoutFieldValue('description');
    }

    public function testItFailsOnUpdatingTestItemWithEmptyDescription()
    {
        $this->itFailsOnUpdatingWithTooShortFieldValue('description', 3);
    }

    public function testItFailsOnUpdatingTestItemWithTooLongDescription()
    {
        $this->itFailsOnUpdatingWithTooLongFieldValue('description', 1024);
    }
}
