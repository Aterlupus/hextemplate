<?php
declare(strict_types=1);

namespace App\Core\Util;

use Webmozart\Assert\Assert;

class Regex
{
    public static function getRegexMatches(string $content, string $pattern): array
    {
        preg_match_all($pattern, $content,$matches, PREG_PATTERN_ORDER);

        return $matches[1];
    }

    public static function getOnlyRegexMatch(string $content, string $pattern): string
    {
        $matches = self::getRegexMatches($content, $pattern);
        Assert::count($matches, 1, sprintf('Invalid %s method result. 1 match expected, got %d', __METHOD__, count($matches)));

        return Set::getOnly($matches);
    }
}
