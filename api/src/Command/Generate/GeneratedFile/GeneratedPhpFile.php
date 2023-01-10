<?php
declare(strict_types=1);

namespace App\Command\Generate\GeneratedFile;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\InterfaceType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class GeneratedPhpFile implements GeneratedFileInterface
{
    private PhpFile $phpFile;

    private PhpNamespace $phpNamespace;

    private ClassLike $phpClass;

    //TODO: rename
    private array $finalReplacements = [];


    public function __construct(
        string $namespace,
        string $classname,
        string $type = 'class'
    ) {
        $this->phpFile = new PhpFile;
        $this->phpFile->setStrictTypes();

        $this->phpNamespace = $this->phpFile->addNamespace($namespace);

        if ('class' === $type) {
            $this->phpClass = $this->phpNamespace->addClass($classname);
        } else if ('interface' === $type) {
            $this->phpClass = $this->phpNamespace->addInterface($classname);
        } else {
            throw new \InvalidArgumentException('invalid type');
        }
    }

    public function addUse(string $class): void
    {
        $this->getPhpNamespace()->addUse($class);
    }

    public function addConstructor(): Method
    {
        $constructor = new Method('__construct');
        $constructor->setPublic();

        $this->phpClass->addMember($constructor);

        return $constructor;
    }

    public function addMethod(
        string $name,
        string $accessibility = 'public',
        string $returnType = 'void',
        array $parameters = [],
        bool $setReturnNullable = false,
        bool $isStatic = false,
        ?string $body = null
    ): void {
        $method = new Method($name);

        switch ($accessibility) {
            case 'public': $method->setPublic(); break;
            case 'protected': $method->setProtected(); break;
            case 'private': $method->setPrivate(); break;
            default: throw new \InvalidArgumentException(sprintf('Unknown $accessibility'));
        }

        $method->setReturnType($returnType);
        $method->setReturnNullable($setReturnNullable);
        $method->setStatic($isStatic);

        foreach ($parameters as $parameter) {
            $method
                ->addParameter($parameter['name'])
                ->setType($parameter['type']);
        }

        if (null !== $body) {
            $method->addBody($body);
        }

        $this->getPhpClass()->addMember($method);
    }

    //TODO: Rename
    public function applyFinalReplacements(string $content): string
    {
        foreach ($this->finalReplacements as $tag => $replacement) {
            $content = str_replace($tag, $replacement, $content);
        }

        return $content;
    }

    //TODO: rename
    public function addFinalReplacement(string $tag, string $replacement): void
    {
        $this->finalReplacements[$tag] = $replacement;
    }

    public function getNamespace(): string
    {
        return $this->getPhpNamespace()->getName();
    }

    public function getClassname(): string
    {
        return $this->getPhpClass()->getName();
    }

    public function getFullClassname(): string
    {
        return sprintf('%s\%s', $this->getNamespace(), $this->getClassname());
    }

    public function getExtension(): string
    {
        return 'php';
    }

    public function getPhpFile(): PhpFile
    {
        return $this->phpFile;
    }

    public function getPhpNamespace(): PhpNamespace
    {
        return $this->phpNamespace;
    }

    public function getPhpClass(): ClassType|InterfaceType
    {
        return $this->phpClass;
    }
}
