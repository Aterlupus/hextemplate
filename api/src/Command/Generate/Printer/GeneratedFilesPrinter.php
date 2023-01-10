<?php
declare(strict_types=1);

namespace App\Command\Generate\Printer;

use App\Command\Generate\Nette\GeneratedFilePrinter;
use App\Command\Generate\GeneratedFile\GeneratedPhpFile;
use App\Command\Generate\GeneratedFile\GeneratedSimpleFile;
use App\Command\Generate\FileGenerator\FileGeneratorInterface;
use InvalidArgumentException;

class GeneratedFilesPrinter
{
    public function __construct(
        private readonly GeneratedFilePrinter $printer
    ) {}

    public function getFileContent(FileGeneratorInterface $fileFactory): string
    {
        $generatedFile = $fileFactory->getFile();

        if (is_a($generatedFile, GeneratedPhpFile::class)) {
            $content = $this->printPhpFile($generatedFile);
            return $generatedFile->applyFinalReplacements($content);
        } else if (is_a($generatedFile, GeneratedSimpleFile::class)) {
            return $this->printSimpleFile($generatedFile);
        } else {
            throw new InvalidArgumentException(sprintf('Unknown GeneratedFileInterface type "%s"', get_class($generatedFile)));
        }
    }

    private function printPhpFile(GeneratedPhpFile $phpFile): string
    {
        return $this->printer->printFile($phpFile->getPhpFile());
    }

    private function printSimpleFile(GeneratedSimpleFile $simpleFile): string
    {
        return $simpleFile->getFileContent();
    }
}
