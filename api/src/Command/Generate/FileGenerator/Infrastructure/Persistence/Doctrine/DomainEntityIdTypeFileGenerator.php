<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Infrastructure\Persistence\Doctrine;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainPropertyFileGenerator;
use App\Shared\Infrastructure\Persistence\Doctrine\UuidType;

class DomainEntityIdTypeFileGenerator extends AbstractFileGenerator
{
    public function populatePhpFile(): void
    {
        $this->addUse(UuidType::class);
        $this->addExtends(UuidType::class);

        $this->addGetTypeClass();
    }

    private function addGetTypeClass(): void
    {
        $this->getFile()->addMethod(
            'getTypeClass',
            'protected',
            'string',
            body: sprintf('return %sId::class;', $this->getDomain())
        );
    }

    public function addDomainEntityIdUse(DomainPropertyFileGenerator $domainIdGeneratedFile): void
    {
        $this->addUse($domainIdGeneratedFile->getFullClassname());
    }

    protected function getClassname(): string
    {
        return sprintf('%sIdType', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Infrastructure\Persistence\Doctrine', $this->getDomain());
    }
}
