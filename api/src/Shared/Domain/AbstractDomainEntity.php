<?php
declare(strict_types=1);

namespace App\Shared\Domain;

use JsonSerializable;

abstract class AbstractDomainEntity implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return DomainEntityToArrayConverter::convert($this);
    }

    public static function getEntityFields(): array
    {
        return array_keys(
            get_class_vars(static::class)
        );
    }
}
