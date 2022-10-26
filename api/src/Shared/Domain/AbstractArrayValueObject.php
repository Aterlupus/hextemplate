<?php
declare(strict_types=1);

namespace App\Shared\Domain;

use JsonSerializable;

//TODO: Implement Traversable/Iterable
abstract class AbstractArrayValueObject implements JsonSerializable
{
    public function __construct(
        protected readonly array $value = []
    ) {}

    public function getValue(): array
    {
        return $this->value;
    }

    public function getValues(): array
    {
        return array_map(
            fn($element) => $element->getValue(), //TODO: Implement general domain property interface?
            $this->getValue()
        );
    }

    public function jsonSerialize(): array
    {
        return $this->getValue();
    }
}
