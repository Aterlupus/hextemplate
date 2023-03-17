<?php
declare(strict_types=1);

namespace App\Command\Generate\Nette;

use Nette\PhpGenerator\Attribute;
use Nette\PhpGenerator\Closure;
use Nette\PhpGenerator\GlobalFunction;
use Nette\PhpGenerator\Helpers;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer as NettePrinter;
use Nette\Utils\Strings;

class GeneratedFilePrinter extends NettePrinter
{
    public string $indentation = "    ";

    public int $linesBetweenMethods = 1;

    private bool $resolveTypes = true;


    public function printFile(PhpFile $file): string
    {
        $namespaces = [];
        foreach ($file->getNamespaces() as $namespace) {
            $namespaces[] = $this->printNamespace($namespace);
        }

        return "<?php\n"
            . ($file->getComment() ? "\n" . $this->printDocComment($file) . "\n" : '')
            . ($file->hasStrictTypes() ? "declare(strict_types=1);\n\n" : '')
            . implode("\n\n", $namespaces);
    }

    public function printMethod(Method $method, ?PhpNamespace $namespace = null, bool $isInterface = false): string
    {
        $this->namespace = $this->resolveTypes ? $namespace : null;
        $method->validate();
        $line = ($method->isAbstract() && !$isInterface ? 'abstract ' : '')
            . ($method->isFinal() ? 'final ' : '')
            . ($method->getVisibility() ? $method->getVisibility() . ' ' : '')
            . ($method->isStatic() ? 'static ' : '')
            . 'function '
            . ($method->getReturnReference() ? '&' : '')
            . $method->getName();
        $returnType = $this->printReturnType($method);
        $params = $this->printParameters($method, strlen($line) + strlen($returnType) + strlen($this->indentation) + 2);
        $body = Helpers::simplifyTaggedNames($method->getBody(), $this->namespace);
        $body = ltrim(rtrim(Strings::normalize($body)) . "\n");
        $braceOnNextLine = $this->bracesOnNextLine && !str_contains($params, "\n");

        $methodString = $this->printDocComment($method)
            . $this->printAttributes($method->getAttributes())
            . $line
            . $params
            . $returnType;

        if ('__construct' === $method->getName()) {
            $methodString .= ($method->isAbstract() || $isInterface
                ? ";\n"
                : ($braceOnNextLine ? "\n" : ' ') . "{" . $this->indent($body) . "}\n");
        } else {
            $methodString .= ($method->isAbstract() || $isInterface
                ? ";\n"
                : ($braceOnNextLine ? "\n" : ' ') . "{\n" . $this->indent($body) . "}\n");
        }

        return $methodString;
    }

    protected function printReturnType(Closure|GlobalFunction|Method $function): string
    {
        return ($tmp = $this->printType($function->getReturnType(), $function->isReturnNullable()))
            ? $this->returnTypeColon . $tmp
            : '';
    }

    /** @param Attribute[]  $attrs */
    protected function printAttributes(array $attrs, bool $inline = false): string
    {
        return parent::printAttributes($attrs, false);
    }

    protected function printType(?string $type, bool $nullable): string
    {
        if ($type === null) {
            return '';
        }

        if ($this->namespace) {
            $type = $this->namespace->simplifyType($type);
        }

        if (1 <= substr_count($type, '\\')) {
            $type = ltrim($type, '\\');
        }

        if ($nullable && strcasecmp($type, 'mixed')) {
            $type = str_contains($type, '|')
                ? $type . '|null'
                : '?' . $type;
        }

        return $type;
    }
}
