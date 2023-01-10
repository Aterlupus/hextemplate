<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Application\Get;

use App\Command\Generate\FileGenerator\AbstractFileGenerator;
use App\Shared\Application\CQRS\QueryInterface;

class DomainEntityQueryFileGenerator extends AbstractFileGenerator
{
    protected function populatePhpFile(): void
    {
        $this->addImplements(QueryInterface::class);

        $this->addConstructor();
        $this->addIdGetter();
    }

    private function addConstructor(): void
    {
        $constructor = $this->getFile()->addConstructor();

        $parameter = $constructor->addPromotedParameter(
            sprintf('%sId', lcfirst($this->getDomain())),
        );
        $parameter->setReadOnly();
        $parameter->setType('string');
    }

    private function addIdGetter(): void
    {
        $this->getFile()->addMethod(
            sprintf('get%sId', ucfirst($this->getDomain())),
            'public',
            'string',
            body: sprintf('return $this->%sId;', lcfirst($this->getDomain())),
        );
    }

    protected function getClassname(): string
    {
        return sprintf('%sQuery', $this->getDomain());
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Application\Get', $this->getDomain());
    }
}
