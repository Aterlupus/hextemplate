<?php
declare(strict_types=1);

namespace App\Core\Suit;

use ReflectionClass;

abstract class AbstractConstantsSuit extends AbstractSuit
{
    protected static function loadAllValues(): array
    {
        $reflection = new ReflectionClass(static::class);
        return array_values($reflection->getConstants());
    }
}
