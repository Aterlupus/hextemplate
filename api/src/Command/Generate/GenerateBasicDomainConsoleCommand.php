<?php
declare(strict_types=1);

namespace App\Command\Generate;

use App\Command\AbstractConsoleCommand;
use App\Command\Generate\FileGenerator\DomainFilesGenerator;
use App\Command\Generate\Nette\GeneratedFilePrinter;
use App\Command\Generate\Structure\DomainEntityProperties;
use App\Command\Generate\Structure\DomainEntityProperty;
use App\Command\Generate\Printer\GeneratedFilesCreator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class GenerateBasicDomainConsoleCommand extends AbstractConsoleCommand
{
    private GeneratedFilesCreator $filesCreator;

    private bool $printCreatedFiles = false;


    public function __construct(
        private readonly GeneratedFilePrinter $printer
    ) {
        parent::__construct();
        $this->filesCreator = new GeneratedFilesCreator($printer, true);
    }

    protected function configure(): void
    {
        parent::configure();
        $this->setName('app:generate:domain');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $yaml = Yaml::parse(file_get_contents('./src/Command/Generate/schema/TestTag.yaml'));
        $domain = $yaml['domain'];
        $properties = self::createDomainEntityProperties($yaml['properties']);

        $domainFilesGenerator = new DomainFilesGenerator($domain, $properties);
        $files = $domainFilesGenerator->generateDomain();

        $this->createFiles($files);

        return 0;
    }

    private static function createDomainEntityProperties(array $yamlProperties): DomainEntityProperties
    {
        $properties = new DomainEntityProperties;
        foreach ($yamlProperties as $propertyName => $property) {
            $properties->add(new DomainEntityProperty(
                $propertyName,
                $property['type'],
                $property['minLength'] ?? null,
                $property['maxLength'] ?? null,
                $property['external'] ?? false,
                $property['externalDomain'] ?? null,
                $property['items']['type'] ?? null,
            ));
        }

        return $properties;
    }

    private function createFiles(array $files): void
    {
        foreach ($files as $file) {
            if ($this->printCreatedFiles) {
                echo ">>>>>>>>>>FILE<<<<<<<<<<\n";
                echo $this->printer->printFile($file->getPhpFile());
            }
            $this->filesCreator->createFile($file);
        }
    }
}