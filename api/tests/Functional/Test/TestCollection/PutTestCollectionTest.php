<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestCollection;

use App\Shared\Domain\AbstractDomainEntity;
use Test\Functional\AbstractPutFunctionalTest;

class PutTestCollectionTest extends AbstractPutFunctionalTest
{
    use TestCollectionTestTrait;

    private const TEST_COLLECTION = '/api/test_collections/%s.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    protected function createEntity(): AbstractDomainEntity
    {
        return $this->eg->getTestCollection();
    }

    public function testItUpdatesTestCollection()
    {
        $this->itUpdates();
    }

    public function testItFailsOnUpdatingTestCollectionWithoutDescription()
    {
        $this->itFailsOnUpdatingWithoutFieldValue('name');
    }

    public function testItFailsOnUpdatingTestCollectionWithEmptyDescription()
    {
        $this->itFailsOnUpdatingWithTooShortFieldValue('name', 3);
    }

    public function testItFailsOnUpdatingTestCollectionWithTooLongDescription()
    {
        $this->itFailsOnUpdatingWithTooLongFieldValue('name', 255);
    }
}
