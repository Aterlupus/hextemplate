<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Domain;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Shared\Domain\AbstractArrayValueObject;
use App\Shared\Domain\AbstractBooleanValueObject;
use App\Shared\Domain\AbstractIntegerValueObject;
use App\Shared\Domain\AbstractNullableStringValueObject;
use App\Shared\Domain\AbstractStringValueObject;

class DomainPropertyFileGenerator extends AbstractFileGenerator
{
    private const TYPE_TO_PROPERTY_CLASS = [
        'int' => AbstractIntegerValueObject::class,
        'bool' => AbstractBooleanValueObject::class,
        'string' => AbstractStringValueObject::class,
        '?string' => AbstractNullableStringValueObject::class,
        'array' => AbstractArrayValueObject::class,
    ];


    public function __construct(
        string $domain,
        private readonly string $propertyName,
        private readonly string $propertyType,
    ) {
        parent::__construct($domain);
    }

    public function populatePhpFile(): void
    {
        $abstractPropertyClass = self::TYPE_TO_PROPERTY_CLASS[$this->propertyType];

        $this->setFinal();
        $this->addExtends($abstractPropertyClass);
        $this->addUse($abstractPropertyClass);
    }

    protected function getClassname(): string
    {
        return sprintf('%s%s', $this->getDomain(), ucfirst($this->propertyName));
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Domain', $this->getDomain());
    }
}
