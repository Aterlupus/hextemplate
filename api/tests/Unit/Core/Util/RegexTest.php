<?php
declare(strict_types=1);

namespace Test\Unit\Core\Util;

use App\Core\Util\Regex;
use App\Core\Util\Set;
use InvalidArgumentException;
use Test\Unit\Shared\AbstractUnitTest;

class RegexTest extends AbstractUnitTest
{
    public function testItGetsMatch()
    {
        $pattern = '/"(.*?)"/';
        $string = '<a href="https://address.com" title="Address"></a>';
        $matches = Regex::getRegexMatch($string, $pattern);

        self::assertCount(1, $matches);
        self::assertEquals('https://address.com', Set::getOnly($matches));
    }

    public function testItGetsOnlyRegexMatch()
    {
        $pattern = '/"(.*?com)"/';
        $string = '<a href="https://address.com" title="Address"></a>';
        $match = Regex::getOnlyRegexMatch($string, $pattern);

        self::assertEquals('https://address.com', $match);
    }

    public function testGetOnlyRegexMatchFailsOnMultipleMatches()
    {
        try {
            $pattern = '/"(.*?)".*"(.*?)"/';
            $string = '<a href="https://address.com" title="Address"></a>';
            Regex::getOnlyRegexMatch($string, $pattern);
        } catch (InvalidArgumentException $exception) {
            self::assertEquals('Invalid App\Core\Util\Regex::getOnlyRegexMatch method result. 1 match expected, got 2', $exception->getMessage());
            return;
        }

        self::fail(sprintf('Should\'ve failed with %s exception', InvalidArgumentException::class));
    }

    public function testItReplaces()
    {
        $pattern = '(bb)';
        $string = 'aabbcc';

        $newString = Regex::replace($string, $pattern, 'xx');

        self::assertEquals('aaxxcc', $newString);
    }

    public function testItReplacesWithGroup()
    {
        $pattern = '(bb)';
        $string = 'aabbcc';

        $newString = Regex::replace($string, $pattern, 'x${0}x');

        self::assertEquals('aaxbbxcc', $newString);
    }
}
