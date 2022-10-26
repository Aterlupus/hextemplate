<?php
declare(strict_types=1);

namespace App\Core\Util;

use Webmozart\Assert\Assert;

class Regex
{
    public static function getRegexMatches(string $content, string $pattern): array
    {
        preg_match($pattern, $content,$matches);
        return $matches;
    }

    public static function getOnlyRegexMatch(string $content, string $pattern): string
    {
        $matches = self::getRegexMatches($content, $pattern);
        Assert::count($matches, 2); //TODO: Message?

        return $matches[1];
    }
}
