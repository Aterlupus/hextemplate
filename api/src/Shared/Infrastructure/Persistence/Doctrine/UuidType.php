<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Core\Util\Set;
use App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer\DoctrineCustomTypeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

//TODO: Refactor
abstract class UuidType extends StringType implements DoctrineCustomTypeInterface
{
    abstract protected function getTypeClass(): string;

    public function getName(): string
    {
        $className = Set::getLast(explode('\\', $this->getTypeClass()));
        $parts = preg_split('#(?=[A-Z])#', $className, flags:PREG_SPLIT_NO_EMPTY);
        $parts = array_map(
            fn(string $part) => strtolower($part),
            $parts
        );

        return implode('_', $parts);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return new ($this->getTypeClass())($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return is_string($value) ? $value : $value->getValue();
    }
}
