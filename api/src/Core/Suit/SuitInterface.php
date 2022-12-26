<?php
declare(strict_types=1);

namespace App\Core\Suit;

interface SuitInterface
{
    public static function getValues(): array;

    public static function hasValue($value): bool;

    public static function assertHasValue($value): void;

    public static function assertHasValues(iterable $values): void;

    public static function count(): int;
}
