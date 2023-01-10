<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Application\Create;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Command\Generate\Structure\DomainEntityProperties;
use App\Command\Generate\Structure\DomainEntityProperty;
use App\Core\Uuid;
use App\Shared\Application\CQRS\CommandInterface;
use App\Shared\Application\CQRS\CreatableFromArrayInterface;

class CreateDomainEntityCommandFileGenerator extends AbstractFileGenerator
{
    public function __construct(
        string $domain,
        private readonly DomainEntityProperties $properties
    ) {
        parent::__construct($domain);
    }

    protected function populatePhpFile(): void
    {
        $this->addImplements(CommandInterface::class);
        $this->addImplements(CreatableFromArrayInterface::class);
        $this->addUse(Uuid::class);

        $this->addConstructor();
        $this->addCreateFromArrayMethod();

        foreach ($this->properties as $property) {
            $this->addPropertyGetter($property);
        }
    }

    private function addConstructor(): void
    {
        $constructor = $this->getFile()->addConstructor();

        /** @var DomainEntityProperty $property */
        foreach ($this->properties as $property) {
            $parameter = $constructor->addPromotedParameter($property->getName());
            $parameter->setReadOnly();
            $parameter->setType($property->getType());
            $parameter->setNullable($property->isNullable());
        }
    }

    private function addCreateFromArrayMethod(): void
    {
        $parametersString = '';
        /** @var DomainEntityProperty $property */
        foreach ($this->properties->getAllWithoutId() as $property) {
            $parametersString .= sprintf("\t\$data['%s'],", $property->getName()) . "\n";
        }

        $this->getFile()->addMethod(
            'createFromArray',
            'public',
            'self',
            parameters: [[
                'name' => 'data',
                'type' => 'array',
            ]],
            isStatic: true,
            body: sprintf(<<<'EOD'
            return new self(
            Uuid::string(),
        %s);
        EOD, $parametersString),
        );
    }

    private function addPropertyGetter(DomainEntityProperty $property): void
    {
        $this->getFile()->addMethod(
            sprintf('get%s', ucfirst($property->getName())),
            'public',
            $property->getType(),
            setReturnNullable: $property->isNullable(),
            body: sprintf('return $this->%s;', $property->getName()),
        );
    }

    protected function getClassname(): string
    {
        return sprintf('Create%sCommand', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Application\Create', $this->getDomain());
    }
}
