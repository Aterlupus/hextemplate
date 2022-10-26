<?php
declare(strict_types=1);

namespace App\Core\Util;

use LogicException;
use Webmozart\Assert\Assert;

class Set
{
    public static function isAny(iterable $set, callable $condition): bool
    {
        foreach ($set as $key => $element) {
            if (true === $condition($element, $key)) {
                return true;
            }
        }

        return false;
    }

    public static function isEvery(iterable $set, callable $condition): bool
    {
        foreach ($set as $key => $element) {
            if (false === $condition($element, $key)) {
                return false;
            }
        }

        return true;
    }

    public static function getOnly(array $set)
    {
        if (1 < count($set)) {
            throw new LogicException('Given set should contain only one element');
        } else if (1 > count($set)) {
            throw new LogicException('Given set shouldn\'t be empty');
        }

        return reset($set);
    }

    public static function getFirst(array $set)
    {
        Assert::notEmpty($set, 'Given set should contain at least one element');

        return reset($set);
    }

    public static function getFirstWhich(array $set, callable $condition)
    {
        foreach ($set as $item) {
            if (true === $condition($item)) {
                return $item;
            }
        }

        return null;
    }

    public static function getFirstNotEmpty(array $set)
    {
        return self::getFirstWhich($set, function ($element) {
            return false === empty($element);
        });
    }

    public static function getLast(array $set)
    {
        Assert::notEmpty($set, 'Given set should contain at least one element');

        return end($set);
    }

    public static function mapKeysAndValues(
        iterable $elements,
        callable $getKey,
        callable $getValue,
    ): array {
        $errors = [];
        foreach ($elements as $element) {
            $key = $getKey($element);
            $value = $getValue($element);

            $errors[$key] = $value;
        }

        return $errors;
    }
}
