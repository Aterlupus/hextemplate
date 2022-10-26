<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestCollection;

use App\TestCollection\Domain\TestCollection;
use Test\Functional\AbstractPostFunctionalTest;
use Test\Functional\RequestJson;
use Test\Helper\Random;

class PostTestCollectionTest extends AbstractPostFunctionalTest
{
    private const TEST_COLLECTION = '/api/test_collections.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    protected static function getPostJson(): RequestJson
    {
        return self::json()
            ->set('name', Random::getString());
    }

    public function testItCreatesTestCollection()
    {
        $this->itCreates(TestCollection::class);
    }

    public function testItFailsOnCreatingTestCollectionWithoutName()
    {
        $this->itFailsOnCreatingWithoutFieldValue('name');
    }

    public function testItFailsOnCreatingTestCollectionWithEmptyName()
    {
        $this->itFailsOnCreatingWithTooShortFieldValue('name', 3);
    }

    public function testItFailsOnCreatingTestCollectionWithTooLongName()
    {
        $this->itFailsOnCreatingWithTooLongFieldValue('name', 255);
    }
}
