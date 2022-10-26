<?php
declare(strict_types=1);

namespace App\Core\Suit;

use App\Core\Exception\InvalidSuitKey\InvalidMonthException;
use App\Core\Util\Set;

class MonthsSuit extends AbstractSuit
{
    protected static function loadAllValues(): array
    {
        return range(1, 12);
    }

    public static function getFirst(): int
    {
        return Set::getFirst(self::getValues());
    }

    public static function getLast(): int
    {
        return Set::getLast(self::getValues());
    }

    public static function isFirst(int $month): bool
    {
        self::assertHasValue($month);
        return self::getFirst() === $month;
    }

    public static function isLast(int $month): bool
    {
        self::assertHasValue($month);
        return self::getLast() === $month;
    }

    protected static function getInvalidKeyExceptionClass(): string
    {
        return InvalidMonthException::class;
    }
}
