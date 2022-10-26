<?php
declare(strict_types=1);

namespace Test\Helper;

use App\Core\Uuid;
use DateTime;
use InvalidArgumentException;

class Random
{
    private const INT_MIN = -2147483648;
    private const INT_MAX = 2147483647;

    private const HTTP_METHODS = ['GET', 'POST', 'PUT', 'DELETE'];


    public static function chance(float $probability = 0.5): bool
    {
        if (0.0 >= $probability) {
            return false;
        } else if ($probability > 1.0) {
            throw new InvalidArgumentException(sprintf('Invalid probability: %f', $probability));
        } else {
            $length = (int) (1 / $probability) * 100;
            $randomPosition = self::getInteger(1, $length);

            return $randomPosition <= $probability * $length;
        }
    }
    
    public static function getInteger(int $min = self::INT_MIN, int $max = self::INT_MAX): int
    {
        return mt_rand($min, $max);
    }

    public static function getPositiveInteger(int $max = self::INT_MAX): int
    {
        return self::getInteger(1, $max);
    }

    public static function getUuids(int $count): array
    {
        return array_map(
            fn(int $number) => Uuid::string(),
            range(1, $count)
        );
    }

    public static function getString(int $length = 8, bool $alphanumeric = false): string
    {
        $x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($alphanumeric) {
            $x .= '0123456789';
        }

        return substr(str_shuffle(str_repeat($x, (int) ceil($length/strlen($x)) )),1,$length);
    }

    public static function getPastDate(): DateTime
    {
        $currentTimestamp = time();
        $randomTimestamp = self::getPositiveInteger($currentTimestamp);

        return new DateTime(date( 'm/d/Y', $randomTimestamp));
    }

    public static function getHttpMethod(): string
    {
        return self::getArrayElement(self::HTTP_METHODS);
    }

    public static function getArrayElement(array $elements): mixed
    {
        $index = mt_rand(0, count($elements) - 1);
        return $elements[$index];
    }
}
