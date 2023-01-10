<?php
declare(strict_types=1);

namespace App\Command\Generate\FileGenerator;

use App\Command\Generate\GeneratedFile\GeneratedPhpFile;
use Nette\PhpGenerator\PhpNamespace;

abstract class AbstractFileGenerator implements FileGeneratorInterface
{
    private GeneratedPhpFile $phpFile;

    //TODO: rename
    private array $finalReplacements = [];


    abstract protected function populatePhpFile(): void;

    abstract protected function getClassname(): string;

    abstract protected function getNamespace(): string;

    public function __construct(
        private readonly string $domain,
    ) {
        $this->createFile();
    }

    public function createFile(): void
    {
        $this->phpFile = new GeneratedPhpFile($this->getNamespace(), $this->getClassname(), $this->getFileType());

        $this->populatePhpFile();

        foreach ($this->finalReplacements as $tag => $replacement) {
            $this->phpFile->addFinalReplacement($tag, $replacement);
        }
    }

    protected function setFinal(bool $isFinal = true): void
    {
        $this->getFile()->getPhpClass()->setFinal($isFinal);
    }

    protected function addExtends(string $class): void
    {
        $this->addUse($class);
        switch ($this->getFileType()) {
            case 'class': $this->getFile()->getPhpClass()->setExtends($class); break;
            case 'interface': $this->getFile()->getPhpClass()->addExtend($class); break;
            default: throw new \InvalidArgumentException(sprintf('Unknown file type "%s"', $this->getFileType()));
        }
    }

    protected function addImplements(string $class): void
    {
        $this->addUse($class);
        $this->getFile()->getPhpClass()->addImplement($class);
    }

    protected function addUse(string $class, ?string $alias = null, string $of = PhpNamespace::NameNormal): void
    {
        $this->getFile()->getPhpNamespace()->addUse($class, $alias, $of);
    }

    //TODO: rename
    public function addFinalReplacement(string $tag, string $replacement): void
    {
        $this->finalReplacements[$tag] = $replacement;
    }

    public function getFullClassname(): string
    {
        return sprintf('%s\%s', $this->getNamespace(), $this->getClassname());
    }

    public function getFile(): GeneratedPhpFile
    {
        return $this->phpFile;
    }

    protected function getFileType(): string
    {
        return 'class';
    }

    protected function getDomain(): string
    {
        return $this->domain;
    }
}
