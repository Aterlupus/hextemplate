<?php
declare(strict_types=1);

namespace App\Core\Suit;

use App\Core\Exception\InvalidSuitKeyException;
use App\Core\Exception\UnallowedSuitKeyException;
use Webmozart\Assert\Assert;

abstract class AbstractSuit implements SuitInterface
{
    private static array $suitValues;


    abstract protected static function loadAllValues(): array;

    public static function getAllValues(): array
    {
        if (false === isset(self::$suitValues[static::class])) {
            self::$suitValues[static::class] = static::loadAllValues();
        }

        return self::$suitValues[static::class];
    }

    public static function getValues(): array
    {
        return array_values(static::getAllValues());
    }

    public static function hasValue($value): bool
    {
        return in_array($value, self::getValues());
    }

    /**
     * @throws InvalidSuitKeyException
     */
    protected static function isOneOf($value, $suitValues): bool
    {
        self::assertHasValue($value);
        return in_array($value, $suitValues);
    }

    /**
     * @throws InvalidSuitKeyException
     * @throws UnallowedSuitKeyException
     */
    protected static function assertIsOneOf($value, $suitValues): void
    {
        if (false === self::isOneOf($value, $suitValues)) {
            self::throwUnallowedSuitKeyException($value);
        }
    }

    protected static function getInvalidKeyExceptionClass(): string
    {
        return InvalidSuitKeyException::class;
    }

    /**
     * @throws InvalidSuitKeyException
     */
    protected static function throwInvalidSuitKeyException($value): never
    {
        throw static::getInvalidSuitKeyException($value);
    }

    public static function getInvalidSuitKeyException($value): InvalidSuitKeyException
    {
        $exceptionClass = static::getInvalidKeyExceptionClass();
        self::assertIsExceptionClassSubclassOf($exceptionClass, InvalidSuitKeyException::class);
        return new $exceptionClass(static::class, $value);
    }

    protected static function getUnallowedSuitKeyExceptionClass(): string
    {
        return UnallowedSuitKeyException::class;
    }

    /**
     * @throws UnallowedSuitKeyException
     */
    protected static function throwUnallowedSuitKeyException($value): void
    {
        throw static::getUnallowedSuitKeyException($value);
    }

    public static function getUnallowedSuitKeyException($value): UnallowedSuitKeyException
    {
        $exceptionClass = static::getUnallowedSuitKeyExceptionClass();
        self::assertIsExceptionClassSubclassOf($exceptionClass, UnallowedSuitKeyException::class);
        return new $exceptionClass($value);
    }

    private static function assertIsExceptionClassSubclassOf(string $actualClass, string $expectedSubclass): void
    {
        Assert::true(is_a($actualClass, $expectedSubclass, true));
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function assertHasValue($value): void
    {
        if (false === self::hasValue($value)) {
            static::throwInvalidSuitKeyException($value);
        }
    }

    /**
     * @throws InvalidSuitKeyException
     */
    public static function assertHasValues(iterable $values): void
    {
        foreach ($values as $value) {
            static::assertHasValue($value);
        }
    }

    public static function count(): int
    {
        return count(static::getValues());
    }
}
