<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Application\Get;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainEntityFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainPropertyFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainRepositoryInterfaceFileGenerator;
use App\Shared\Application\CQRS\QueryHandlerInterface;

class DomainEntityQueryHandlerFileGenerator extends AbstractFileGenerator
{
    private const INVOKE_BODY_FORMAT = <<<'EOD'
                return $this->%sRepository->get(
                new %sId($query->get%sId())
            );
            EOD;


    protected function populatePhpFile(): void
    {
        $this->addImplements(QueryHandlerInterface::class);

        $this->addConstructor();
        $this->addInvoke();
    }

    private function addConstructor(): void
    {
        $construct = $this->getFile()->addConstructor();

        $parameter = $construct->addPromotedParameter(sprintf('%sRepository', lcfirst($this->getDomain())));
        $parameter->setReadOnly();
        $parameter->setType(sprintf('%sRepositoryInterface', $this->getDomain()));
    }

    private function addInvoke(): void
    {
        $this->getFile()->addMethod(
            '__invoke',
            'public',
            $this->getDomain(),
            [
                [
                    'name' => 'query',
                    'type' => sprintf('%sQuery', $this->getDomain()),
                ]
            ],
            setReturnNullable: true,
            body: sprintf(self::INVOKE_BODY_FORMAT,
                lcfirst($this->getDomain()),
                $this->getDomain(),
                $this->getDomain(),
            )
        );
    }

    protected function getClassname(): string
    {
        return sprintf('%sQueryHandler', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Application\Get', $this->getDomain());
    }

    public function addDomainEntityUse(DomainEntityFileGenerator $domainEntityGeneratedFile): void
    {
        $this->addUse($domainEntityGeneratedFile->getFullClassname());
    }

    public function addDomainEntityIdUse(DomainPropertyFileGenerator $phpFile): void
    {
        $this->addUse($phpFile->getFullClassname());
    }

    public function addDomainEntityRepositoryInterfaceUse(DomainRepositoryInterfaceFileGenerator $phpFile): void
    {
        $this->addUse($phpFile->getFullClassname());
    }
}
