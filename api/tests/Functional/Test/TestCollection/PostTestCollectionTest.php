<?php
declare(strict_types=1);

namespace Test\Functional\Test\TestCollection;

use Test\Functional\AbstractPostFunctionalTest;

class PostTestCollectionTest extends AbstractPostFunctionalTest
{
    use TestCollectionTestTrait;

    private const TEST_COLLECTION = '/api/test_collections.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    public function testItCreatesTestCollection()
    {
        $this->itCreates();
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
