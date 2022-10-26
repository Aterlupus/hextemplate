<?php
declare(strict_types=1);

namespace App\Shared\Domain;

use App\Core\Uuid;
use Stringable;

abstract class AbstractStringValueObject implements Stringable
{
    public function __construct(
        protected string $value
    ) {}

    public static function new(): static
    {
        return new static(Uuid::string());
    }

    public function equals(self $stringVO): bool
    {
        return $this->getValue() === $stringVO->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
