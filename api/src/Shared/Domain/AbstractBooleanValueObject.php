<?php
declare(strict_types=1);

namespace App\Shared\Domain;

abstract class AbstractBooleanValueObject
{
    public function __construct(
        protected readonly bool $value
    ) {}

    public function equals(self $booleanValueObject): bool
    {
        return $this->getValue() === $booleanValueObject->getValue();
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
