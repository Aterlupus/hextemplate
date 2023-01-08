<?php
declare(strict_types=1);

namespace App\Shared\Domain;

use App\Core\Uuid;

class AbstractNullableStringValueObject
{
    public function __construct(
        protected ?string $value
    ) {}

    public static function new(): static
    {
        return new static(Uuid::string());
    }

    public function equals(self $stringVO): bool
    {
        return $this->getValue() === $stringVO->getValue();
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
