<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Application\Create;

use App\Command\Generate\Structure\DomainEntityProperties;
use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainRepositoryInterfaceFileGenerator;
use App\Command\Generate\Structure\DomainEntityProperty;
use App\Shared\Application\CQRS\AbstractCommandHandler;
use Nette\PhpGenerator\Method;

class CreateDomainEntityCommandHandlerFileGenerator extends AbstractFileGenerator
{
    private const GET_EXTERNAL_ENTITIES_FORMAT = <<<'EOD'
    foreach ($command->get%s() as $%sId) {
        $%s = $this->%sRepository->get(new %sId($%sId));
        self::assertEntityNotNull($%sId, %s::class, $%s);
    }
        
    EOD;

    private const GET_EXTERNAL_ENTITY_FORMAT = <<<'EOD'
        $%s = $this->%sRepository->get(new %sId($command->get%s()));
        self::assertEntityNotNull($command->get%s(), %s::class, $%s);
    EOD;

    private const GET_NULLABLE_EXTERNAL_ENTITY_CONDITION_FORMAT = <<<'EOD'
    if (null !== $command->getParentTagId()) {
    %s
    }
    EOD;

    private const CONSTRUCTOR_BODY_PART_FORMAT = <<<'EOD'
        new %s%s($command->get%s()),
    EOD;

    private const INVOKE_BODY = <<<'EOD'
    %s
    
    $%s = new %s(
    %s);

    $this->%sRepository->save($%s);
    EOD;


    public function __construct(
        string $domain,
        private readonly DomainEntityProperties $properties
    ) {
        parent::__construct($domain);
    }

    protected function populatePhpFile(): void
    {
        $this->addExtends(AbstractCommandHandler::class);

        $this->addUse(sprintf('App\%s\Domain\%s', $this->getDomain(), $this->getDomain()));

        $constructor = $this->addConstructor();
        $this->addInvoke($constructor);
    }

    private function addConstructor(): Method
    {
        $constructor = $this->getFile()->addConstructor();

        $parameter = $constructor->addPromotedParameter(sprintf('%sRepository', lcfirst($this->getDomain())));
        $parameter->setReadOnly();
        $parameter->setType(sprintf('%sRepositoryInterface', $this->getDomain()));

        /** @var DomainEntityProperty $property */
        foreach ($this->properties as $property) {
            if ($property->isExternal()) {
                $parameter = $constructor->addPromotedParameter(sprintf('%sRepository', lcfirst($property->getExternalDomain())));
                $parameter->setReadOnly();
                $parameter->setType(sprintf('%sRepositoryInterface', $property->getExternalDomain()));
            }
        }

        return $constructor;
    }

    private function addInvoke(Method $constructor): void
    {
        $domainConstructorExecutionBody = '';
        /** @var DomainEntityProperty $property */
        foreach ($this->properties as $property) {
            $domainConstructorExecutionBody .= sprintf(
                    self::CONSTRUCTOR_BODY_PART_FORMAT,
                    $this->getDomain(),
                    ucfirst($property->getName()),
                    ucfirst($property->getName()),
                ) . "\n";

            $this->addUse(sprintf('App\%s\Domain\%s%s', $this->getDomain(), $this->getDomain(), ucfirst($property->getName())));
        }

        $getExternalEntityCode = '';
        /** @var DomainEntityProperty $property */
        foreach ($this->properties->getExternals() as $property) {
            $this->addUse(sprintf('App\%s\Domain\%s' ,$property->getExternalDomain(), $property->getExternalDomain()));
            $this->addUse(sprintf('App\%s\Domain\%sRepositoryInterface', $property->getExternalDomain(), $property->getExternalDomain()));
            $this->addUse(sprintf('App\%s\Domain\%sId', $property->getExternalDomain(), $property->getExternalDomain()));

            if ($property->isCollection()) {
                $getExternalEntityCode .= sprintf(
                    self::GET_EXTERNAL_ENTITIES_FORMAT,
                    ucfirst($property->getName()),
                    lcfirst($property->getExternalDomain()),
                    lcfirst($property->getExternalDomain()),
                    lcfirst($property->getExternalDomain()),
                    $property->getExternalDomain(),
                    lcfirst($property->getExternalDomain()),
                    lcfirst($property->getExternalDomain()),
                    $property->getExternalDomain(),
                    lcfirst($property->getExternalDomain()),
                );
            } else {
                $parameter = $constructor->addPromotedParameter(sprintf('%sRepository', lcfirst($property->getExternalDomain())));
                $parameter->setReadOnly();
                $parameter->setType(sprintf('%sRepositoryInterface', $property->getExternalDomain()));

                $getExternalEntityAssertionCode = sprintf(
                    self::GET_EXTERNAL_ENTITY_FORMAT,
                    lcfirst($property->getExternalDomain()),
                    lcfirst($property->getExternalDomain()),
                    $property->getExternalDomain(),
                    ucfirst($property->getName()),
                    ucfirst($property->getName()),
                    $property->getExternalDomain(),
                    lcfirst($property->getExternalDomain()),
                );

                if ($property->isNullable()) {
                    $getExternalEntityAssertionCode = sprintf(
                        self::GET_NULLABLE_EXTERNAL_ENTITY_CONDITION_FORMAT,
                        $getExternalEntityAssertionCode
                    );
                }

                $getExternalEntityCode .= $getExternalEntityAssertionCode;
            }
        }

        $this->getFile()->addMethod(
            '__invoke',
            parameters: [[
                'name' => 'command',
                'type' => sprintf('Create%sCommand', $this->getDomain())
            ]],
            body: sprintf(
                self::INVOKE_BODY,
                $getExternalEntityCode,
                lcfirst($this->getDomain()),
                $this->getDomain(),
                $domainConstructorExecutionBody,
                lcfirst($this->getDomain()),
                lcfirst($this->getDomain()),
            )
        );
    }

    public function addDomainEntityRepositoryInterfaceUse(DomainRepositoryInterfaceFileGenerator $phpFile): void
    {
        $this->addUse($phpFile->getFullClassname());
    }

    protected function getClassname(): string
    {
        return sprintf('Create%sCommandHandler', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Application\Create', $this->getDomain());
    }
}
