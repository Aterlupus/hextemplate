<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Infrastructure\Persistence\Repository;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainEntityFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainPropertyFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainRepositoryInterfaceFileGenerator;
use App\Shared\Infrastructure\Persistence\Repository\AbstractDoctrineRepository;

class DomainEntityDoctrineRepositoryFileGenerator extends AbstractFileGenerator
{
    protected function populatePhpFile(): void
    {
        $this->addExtends(AbstractDoctrineRepository::class);
        $this->addUse(AbstractDoctrineRepository::class);

        $this->addGetEntityClass();
        $this->addGet();
        $this->addSave();
        $this->addDelete();
    }

    private function addGetEntityClass(): void
    {
        $this->getFile()->addMethod(
            'getEntityClass',
            'protected',
            'string',
            body: sprintf('return %s::class;', $this->getDomain())
        );
    }

    private function addGet(): void
    {
        $this->getFile()->addMethod(
            'get',
            'public',
            $this->getDomain(),
            parameters: [[
                'name' => sprintf('%sId', lcfirst($this->getDomain())),
                'type' => sprintf('%sId', $this->getDomain())
            ]],
            setReturnNullable: true,
            body: sprintf('return $this->getRepository()->find($%sId);', lcfirst($this->getDomain()))
        );
    }

    private function addSave(): void
    {
        $this->getFile()->addMethod(
            'save',
            'public',
            'void',
            parameters: [[
                'name' => lcfirst($this->getDomain()),
                'type' => $this->getDomain()
            ]],
            body: sprintf('$this->saveEntity($%s);', lcfirst($this->getDomain()))
        );
    }

    private function addDelete(): void
    {
        $this->getFile()->addMethod(
            'delete',
            'public',
            'void',
            parameters: [[
                'name' => lcfirst($this->getDomain()),
                'type' => $this->getDomain()
            ]],
            body: sprintf('$this->deleteEntity($%s);', lcfirst($this->getDomain()))
        );
    }

    public function addDomainEntityUse(DomainEntityFileGenerator $domainEntityGeneratedFile): void
    {
        $this->addUse($domainEntityGeneratedFile->getFullClassname());
    }

    public function addDomainEntityIdUse(DomainPropertyFileGenerator $domainEntityIdGeneratedFile): void
    {
        $this->addUse($domainEntityIdGeneratedFile->getFullClassname());
    }

    public function addImplementsDomainEntityRepositoryInterface(DomainRepositoryInterfaceFileGenerator $domainEntityRepositoryInterface)
    {
        $this->addImplements($domainEntityRepositoryInterface->getFullClassname());
        $this->addUse($domainEntityRepositoryInterface->getFullClassname());
    }

    protected function getClassname(): string
    {
        return sprintf('%sDoctrineRepository', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Infrastructure\Persistence\Repository', $this->getDomain());
    }
}
