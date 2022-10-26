<?php
declare(strict_types=1);

namespace App\Core\Util;

use ReflectionClass;

class TypeInspector
{
    public static function getClassName(object|string $object): string
    {
        return (new ReflectionClass($object))->getShortName();
    }

    public static function getType(mixed $variable): string
    {
        return is_object($variable) ? self::getClassName($variable) : gettype($variable);
    }
}
