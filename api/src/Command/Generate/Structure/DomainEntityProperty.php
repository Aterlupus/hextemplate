<?php
declare(strict_types=1);

namespace App\Command\Generate\Structure;

use App\Core\Util\Regex;
use App\Core\Util\StringParser;

class DomainEntityProperty
{
    public function __construct(
        private readonly string $name,
        private readonly string $type,
        private readonly ?int $minLength,
        private readonly ?int $maxLength,
        private readonly ?string $externalDomain,
        private readonly ?string $itemsType
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function isId(): bool
    {
        return 'id' === $this->getName();
    }

    public function getPropertyType(): string
    {
        return $this->type;
    }

    public function getType(): string
    {
        return Regex::getOnlyRegexMatch($this->type, '#\??(.*)#');
    }

    public function isNullable(): bool
    {
        return StringParser::startsWith($this->type, '?');
    }

    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function isExternal(): bool
    {
        return null !== $this->getExternalDomain();
    }

    public function getExternalDomain(): ?string
    {
        return $this->externalDomain;
    }

    public function getItemsType(): ?string
    {
        return $this->itemsType;
    }

    public function isCollection(): bool
    {
        return null === $this->getItemsType();
    }
}
