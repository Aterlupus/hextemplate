<?php
declare(strict_types=1);

namespace Test\Unit\Core\Util;

use App\Core\Util\StringParser;
use Test\Unit\Shared\AbstractUnitTest;

class StringParserTest extends AbstractUnitTest
{
    /*
     * startsWith
     */

    public static function provideStartsWithWorksProperly(): array
    {
        return [
            ['string', 's', true],
            ['longstring', 'l', true],
            ['xxx', 'y', false],
        ];
    }

    /**
     * @dataProvider provideStartsWithWorksProperly
     */
    public function testStartsWithWorksProperly(string $string, string $needle, bool $doesStartWith)
    {
        self::assertEquals(
            $doesStartWith,
            StringParser::startsWith($string, $needle)
        );
    }
}
