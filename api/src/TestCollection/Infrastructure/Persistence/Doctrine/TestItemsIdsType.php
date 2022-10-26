<?php
declare(strict_types=1);

namespace App\TestCollection\Infrastructure\Persistence\Doctrine;

use App\Shared\Infrastructure\Bundles\DoctrineCustomTypesRegisterer\DoctrineCustomTypeInterface;
use App\TestItem\Domain\TestItemId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

//TODO: Refactor
class TestItemsIdsType extends JsonType implements DoctrineCustomTypeInterface
{
    public function getName(): string
    {
        return 'test_items_ids';
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        $value = array_map(
            fn (TestItemId $id) => $id->getValue(),
            $value
        );

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): array
    {
        $scalars = parent::convertToPHPValue($value, $platform);

        return array_map(
            fn(string $value) => new TestItemId($value),
            $scalars
        );
    }
}
