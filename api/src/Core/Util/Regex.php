<?php
declare(strict_types=1);

namespace App\Core\Util;

use Webmozart\Assert\Assert;

class Regex
{
    public static function getRegexMatch(string $content, string $pattern): array
    {
        preg_match($pattern, $content, $matches);
        unset($matches[0]);

        return array_values($matches);
    }

    public static function getOnlyRegexMatch(string $content, string $pattern): string
    {
        $matches = self::getRegexMatch($content, $pattern);

        Assert::count($matches, 1, sprintf('Invalid %s method result. 1 match expected, got %d', __METHOD__, count($matches)));

        return Set::getOnly($matches);
    }

    public static function replace(string $content, string $pattern, string $replacement): string
    {
        return preg_replace($pattern, $replacement, $content);
    }
}
