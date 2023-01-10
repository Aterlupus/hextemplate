<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator\Infrastructure\Persistence\Doctrine;

use App\Command\Generate\GeneratedFile\GeneratedSimpleFile;
use App\Command\Generate\FileGenerator\FileGeneratorInterface;

class DomainEntityPropertyOrmXmlConfigFileGenerator implements FileGeneratorInterface
{
    private const TYPE_TO_DB_TYPE = [
        'array' => 'json',
    ];

    private const FILE_FORMAT = <<<'EOD'
    <?xml version="1.0" encoding="UTF-8"?>
    <doctrine-mapping xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                      xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                              https://www.doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    
        <embeddable name="%s">
            <field name="value" type="%s" %scolumn="%s" %s/>
        </embeddable>
    
    </doctrine-mapping>

    EOD;

    private GeneratedSimpleFile $file;

    public function __construct(
        private readonly string $domain,
        private readonly string $propertyName,
        private readonly string $type,
        private readonly bool $isNullable,
        private readonly string $additional = ''
    ) {
        $this->createFile();
    }

    public function createFile(): void
    {
        $fullClassname = sprintf('App\%s\Domain\%s%s', $this->domain, $this->domain, ucfirst($this->propertyName));

        $this->file = new GeneratedSimpleFile(
            $this->getFileContent($fullClassname),
            $this->getNamespace(),
            sprintf('%s%s', $this->domain, ucfirst($this->propertyName)),
            'orm.xml'
        );
    }

    private function getFileContent(string $fullClassname): string
    {
        return sprintf(
            self::FILE_FORMAT,
            $fullClassname,
            self::TYPE_TO_DB_TYPE[$this->type] ?? $this->type,
            $this->isNullable ? 'nullable="true" ' : '',
            $this->propertyName,
            $this->additional,
        );
    }

    protected function getNamespace(): string
    {
        return sprintf('App\%s\Infrastructure\Persistence\Doctrine', $this->domain);
    }

    public function getFile(): GeneratedSimpleFile
    {
        return $this->file;
    }
}
