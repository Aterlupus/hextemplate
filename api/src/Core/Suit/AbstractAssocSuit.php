<?php
declare(strict_types=1);

namespace App\Core\Suit;

use App\Core\Exception\InvalidSuitKeyException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionClassConstant;
use RuntimeException;

abstract class AbstractAssocSuit extends AbstractSuit
{
    protected static function loadAllValues(): array
    {
        $reflection = new ReflectionClass(static::class);
        $constantsReflections = self::filterPublicConstants($reflection->getReflectionConstants());

        if (1 !== count($constantsReflections)) {
            throw new InvalidArgumentException(sprintf('Class %s must have single constant that holds the Suit, or implement override getAllValues method', self::class));
        }

        return end($constantsReflections)->getValue();
    }

    private static function filterPublicConstants(array $constantsReflections): array
    {
        return array_filter(
            $constantsReflections,
            function (ReflectionClassConstant $constantsReflection) {
                return false === $constantsReflection->isPrivate();
            }
        );
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function getValue($key)
    {
        static::assertHasKey($key);
        return static::getAllValues()[$key];
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function getValuesByKeys(array $keys): array
    {
        return array_map(
            fn($key) => static::getValue($key),
            $keys
        );
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function getKeysByValues(array $values): array
    {
        return array_map(
            fn($value) => static::getKeyByValue($value),
            $values
        );
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function getConvertedValues(array $keys): array
    {
        return array_map(function($key) {
            return static::getValue($key);
        }, $keys);
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function getKeyByValue($value): string|int|bool
    {
        static::assertHasValue($value);
        $key = array_search($value, self::getAllValues());

        if ($value !== self::getValue($key)) {
            throw new RuntimeException(sprintf('Error on %s in %s for value "%s"', __FUNCTION__, static::class, $value));
        }

        return $key;
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function assertHasKey($key): void
    {
        if(false === self::hasKey($key)) {
            static::throwInvalidSuitKeyException($key);
        }
    }

    public static function getKeys(): array
    {
        return array_keys(static::getAllValues());
    }

    public static function hasKey($key): bool
    {
        return array_key_exists($key, static::getAllValues());
    }
}
