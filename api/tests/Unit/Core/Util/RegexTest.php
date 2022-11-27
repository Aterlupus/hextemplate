<?php
declare(strict_types=1);

namespace Test\Unit\Core\Util;

use App\Core\Util\Regex;
use InvalidArgumentException;
use Test\Unit\Shared\AbstractUnitTest;

class RegexTest extends AbstractUnitTest
{
    public function testItGetsMatches()
    {
        $pattern = '/"(.*?)"/';
        $string = '<a href="https://address.com" title="Address"></a>';
        $matches = Regex::getRegexMatches($string, $pattern);

        self::assertCount(2, $matches);
        self::assertEquals('address.com', $matches[0]);
        self::assertEquals('Address', $matches[1]);
    }

    public function testItGetsOnlyRegexMatch()
    {
        $pattern = '/"(.*?com)"/';
        $string = '<a href="https://address.com" title="Address"></a>';
        $match = Regex::getOnlyRegexMatch($string, $pattern);

        self::assertEquals('address.com', $match);
    }

    public function testGetOnlyRegexMatchFailsOnMultipleMatches()
    {
        try {
            $pattern = '/"(.*?)"/';
            $string = '<a href="https://address.com" title="Address"></a>';
            Regex::getOnlyRegexMatch($string, $pattern);
        } catch (InvalidArgumentException $exception) {
            self::assertEquals('Invalid App\Core\Util\Regex::getOnlyRegexMatch method result. 1 match expected, got 2', $exception->getMessage());
            return;
        }

        self::fail(sprintf('Should\'ve failed with %s exception', InvalidArgumentException::class));
    }
}
