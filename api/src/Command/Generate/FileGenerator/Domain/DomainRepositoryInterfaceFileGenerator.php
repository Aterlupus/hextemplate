<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Domain;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;

class DomainRepositoryInterfaceFileGenerator extends AbstractFileGenerator
{
    protected function populatePhpFile(): void
    {
        $this->addGet();
        $this->addSave();
        $this->addDelete();
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
            setReturnNullable: true
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
            ]]
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
            ]]
        );
    }

    protected function getFileType(): string
    {
        return 'interface';
    }

    protected function getClassname(): string
    {
        return sprintf('%sRepositoryInterface', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Domain', $this->getDomain());
    }
}
