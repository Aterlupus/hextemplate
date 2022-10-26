<?php
declare(strict_types=1);

namespace App\Shared\Domain;

class DomainEntityToArrayConverter
{
    public static function convert(AbstractDomainEntity $entity): array
    {
        $array = [];
        foreach ($entity::getEntityFields() as $field) {
            $array[$field] = self::getEntityFieldValue($entity, $field);
        }

        return $array;
    }

    private static function getEntityFieldValue(AbstractDomainEntity $entity, string $field): mixed
    {
        $value = self::getFieldValue($entity, $field);

        if (is_array($value)) {
            return self::getArrayObjectValues($value);
        } else {
            return $value;
        }
    }

    private static function getFieldValue(AbstractDomainEntity $entity, string $field): mixed
    {
        $getter = self::getFieldGetter($field);
        return $entity->$getter()->getValue();
    }

    private static function getArrayObjectValues(array $value): array
    {
        return array_map(
            fn(object $object) => $object->getValue(),
            $value
        );
    }

    private static function getFieldGetter(string $field): string
    {
        return sprintf('get%s', $field);
    }
}
