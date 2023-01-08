<?php
declare(strict_types=1);

namespace App\Core\Util;

class StringParser
{
    public static function startsWith(string $string, string $needle): bool
    {
        $length = strlen($needle);

        return substr($string, 0, $length) === $needle;
    }
}
