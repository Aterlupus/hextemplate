<?php
declare(strict_types=1);

namespace App\Shared\Domain;

abstract class AbstractIntegerValueObject
{
    public function __construct(
        protected int $value
    ) {}

    public function equals(self $integerVO): bool
    {
        return $this->getValue() === $integerVO->getValue();
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
