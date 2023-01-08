<?php
declare(strict_types=1);

namespace Test\Functional\TestItem;

use Test\Functional\Shared\AbstractPutFunctionalTest;
use Test\Helper\Random;

class PutUpdateCommentTestItemTest extends AbstractPutFunctionalTest
{
    use TestItemTestTrait;

    private const TEST_COLLECTION = '/api/test_items/%s/update-comment.json';


    protected static function getUri(): string
    {
        return self::TEST_COLLECTION;
    }

    public function testItUpdatesTestItemComment()
    {
        $request = self::json()
            ->set('comment', Random::getString(16));

        $this->itUpdates($request);
    }

    public function testItUpdatesTestItemCommentToNull()
    {
        $request = self::json()
            ->set('comment', null);

        $this->itUpdates($request);
    }

    public function testItFailsOnUpdatingTestItemWithTooShortComment()
    {
        $this->itFailsOnUpdatingWithTooShortFieldValue('comment', 16);
    }
}
