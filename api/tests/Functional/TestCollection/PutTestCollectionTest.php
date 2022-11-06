<?php
declare(strict_types=1);

namespace Test\Functional\TestCollection;

use App\Shared\Domain\AbstractDomainEntity;
use Test\Functional\Shared\AbstractPutFunctionalTest;

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

    public function testItFailsOnUpdatingTestCollectionWithoutName()
    {
        $this->itFailsOnUpdatingWithoutFieldValue('name');
    }

    public function testItFailsOnUpdatingTestCollectionWithEmptyName()
    {
        $this->itFailsOnUpdatingWithTooShortFieldValue('name', 3);
    }

    public function testItFailsOnUpdatingTestCollectionWithTooLongName()
    {
        $this->itFailsOnUpdatingWithTooLongFieldValue('name', 255);
    }
}
