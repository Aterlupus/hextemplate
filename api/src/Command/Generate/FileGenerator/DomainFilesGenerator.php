<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator;

use App\Command\Generate\FileGenerator\Application\Create\CreateDomainEntityCommandFileGenerator;
use App\Command\Generate\FileGenerator\Application\Create\CreateDomainEntityCommandHandlerFileGenerator;
use App\Command\Generate\FileGenerator\Application\Get\DomainEntityQueryFileGenerator;
use App\Command\Generate\FileGenerator\Application\Get\DomainEntityQueryHandlerFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainEntityFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainPropertyFileGenerator;
use App\Command\Generate\FileGenerator\Domain\DomainRepositoryInterfaceFileGenerator;
use App\Command\Generate\FileGenerator\Infrastructure\ApiPlatform\DomainEntityResourceFileGenerator;
use App\Command\Generate\FileGenerator\Infrastructure\Persistence\Doctrine\DomainEntityIdTypeFileGenerator;
use App\Command\Generate\FileGenerator\Infrastructure\Persistence\Doctrine\DomainEntityOrmXmlConfigFileGenerator;
use App\Command\Generate\FileGenerator\Infrastructure\Persistence\Doctrine\DomainEntityPropertyOrmXmlConfigFileGenerator;
use App\Command\Generate\FileGenerator\Infrastructure\Persistence\Repository\DomainEntityDoctrineRepositoryFileGenerator;
use App\Command\Generate\Structure\DomainEntityProperties;
use App\Command\Generate\Structure\DomainEntityProperty;

class DomainFilesGenerator
{
    public function __construct(
        private readonly string $domain,
        private readonly DomainEntityProperties $properties
    ) {}

    public function generateDomain(): array
    {
        /** @var DomainEntityProperty $property */
        foreach ($this->properties->getAllWithoutId() as $property) {
            $files[] = new DomainPropertyFileGenerator($this->domain, $property->getName(), $property->getPropertyType());
        }

        $files[] = $domainEntityIdFileGenerator = new DomainPropertyFileGenerator($this->domain, $this->properties->getId()->getName(), $this->properties->getId()->getPropertyType());

        $files[] = $domainEntityFileGenerator = new DomainEntityFileGenerator($this->domain, $this->properties);

        $files[] = $domainRepositoryInterfaceFileGenerator = new DomainRepositoryInterfaceFileGenerator($this->domain);


        $domainEntityIdTypeFileGenerator = new DomainEntityIdTypeFileGenerator($this->domain);
        $files[] = $domainEntityIdTypeFileGenerator;
        $domainEntityIdTypeFileGenerator->addDomainEntityIdUse($domainEntityIdFileGenerator);



        $domainEntityDoctrineRepositoryFileGenerator = new DomainEntityDoctrineRepositoryFileGenerator($this->domain);
        $files[] = $domainEntityDoctrineRepositoryFileGenerator;
        $domainEntityDoctrineRepositoryFileGenerator->addDomainEntityUse($domainEntityFileGenerator);
        $domainEntityDoctrineRepositoryFileGenerator->addDomainEntityIdUse($domainEntityIdFileGenerator);
        $domainEntityDoctrineRepositoryFileGenerator->addImplementsDomainEntityRepositoryInterface($domainRepositoryInterfaceFileGenerator);




        /** @var DomainEntityProperty $property */
        foreach ($this->properties->getAllWithoutId() as $property) {
            $files[] = new DomainEntityPropertyOrmXmlConfigFileGenerator(
                $this->domain,
                $property->getName(),
                $property->getType(),
                $property->isNullable(),
            );
        }


        $files[] = new DomainEntityOrmXmlConfigFileGenerator(
            $this->domain,
            $domainEntityFileGenerator->getFullClassname(),
            $this->properties
        );


        $domainEntityResourceFileGenerator = new DomainEntityResourceFileGenerator(
            $this->domain,
            $this->properties
        );
        $files[] = $domainEntityResourceFileGenerator;
        $domainEntityResourceFileGenerator->addDomainEntityUse($domainEntityFileGenerator);



        $files[] = new DomainEntityQueryFileGenerator($this->domain);

        $files[] = $domainEntityQueryHandlerFileGenerator = new DomainEntityQueryHandlerFileGenerator($this->domain);
        $domainEntityQueryHandlerFileGenerator->addDomainEntityUse($domainEntityFileGenerator);
        $domainEntityQueryHandlerFileGenerator->addDomainEntityIdUse($domainEntityIdFileGenerator);
        $domainEntityQueryHandlerFileGenerator->addDomainEntityRepositoryInterfaceUse($domainRepositoryInterfaceFileGenerator);



        $files[] = new CreateDomainEntityCommandFileGenerator($this->domain, $this->properties);

        $files[] = $createDomainEntityCommandHandlerFileGenerator = new CreateDomainEntityCommandHandlerFileGenerator($this->domain, $this->properties);
        $createDomainEntityCommandHandlerFileGenerator->addDomainEntityRepositoryInterfaceUse($domainRepositoryInterfaceFileGenerator);
        
        return $files;
    }
}
