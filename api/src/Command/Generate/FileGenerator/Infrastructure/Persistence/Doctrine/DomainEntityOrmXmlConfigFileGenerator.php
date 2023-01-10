<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Infrastructure\Persistence\Doctrine;

use App\Command\Generate\Structure\DomainEntityProperties;
use App\Command\Generate\Structure\DomainEntityProperty;
use App\Command\Generate\GeneratedFile\GeneratedFileInterface;
use App\Command\Generate\GeneratedFile\GeneratedSimpleFile;
use App\Command\Generate\FileGenerator\FileGeneratorInterface;
use App\Core\Util\Exploder;

class DomainEntityOrmXmlConfigFileGenerator implements FileGeneratorInterface
{
    private const FILE_FORMAT = <<<'EOD'
    <?xml version="1.0" encoding="UTF-8"?>
    <doctrine-mapping xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                              https://www.doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    
        <entity name="%s" table="%s">
            <id name="id" type="%s_id" column="id" length="36" />
    %s
        </entity>
    
    </doctrine-mapping>

    EOD;

    private const EMBEDDED_PROPERTY_FORMAT = "\t\t".'<embedded name="%s" class="App\%s\Domain\%s%s" use-column-prefix="false" />';

    private GeneratedSimpleFile $file;

    public function __construct(
        private readonly string $domain,
        private readonly string $domainEntityClass,
        private readonly DomainEntityProperties $properties
    ) {
        $this->createFile();
    }

    public function createFile(): void
    {
        $this->file = new GeneratedSimpleFile(
            $this->getFileContent(),
            $this->getNamespace(),
            $this->domain,
            'orm.xml'
        );
    }

    private function getFileContent(): string
    {
        $underscoredDomain = $this->getUnderscoredDomainName();

        return sprintf(
            self::FILE_FORMAT,
            $this->domainEntityClass,
            $underscoredDomain,
            $underscoredDomain,
            $this->getEmbeddedPropertiesCode()
        );
    }

    private function getEmbeddedPropertiesCode(): string
    {
        $embeddedPropertiesStrings = [];
        /** @var DomainEntityProperty $property */
        foreach ($this->properties->getAllWithoutId() as $property) {
            $embeddedPropertiesStrings[] = sprintf(
                self::EMBEDDED_PROPERTY_FORMAT,
                $property->getName(), //TODO: Possibly remake into underscore separated name
                $this->domain,
                $this->domain,
                ucfirst($property->getName())
            );
        }

        return implode("\n", $embeddedPropertiesStrings);
    }

    private function getUnderscoredDomainName(): string
    {
        $domainWords = Exploder::explodeByCapitalLetter($this->domain);
        $underscoredDomain = implode('_', $domainWords);
        return strtolower($underscoredDomain);
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Infrastructure\Persistence\Doctrine', $this->domain);
    }

    public function getFile(): GeneratedFileInterface
    {
        return $this->file;
    }
}
