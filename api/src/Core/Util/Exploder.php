<?php
declare(strict_types=1);

namespace App\Core\Util;

class Exploder
{
    public static function explodeByCapitalLetter(string $string): array
    {
        return preg_split('/(?<=\\w)(?=[A-Z])/', $string);
    }
}
