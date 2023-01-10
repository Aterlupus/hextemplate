<?php
declare(strict_types=1);

namespace App\Command\Generate\Printer;

use App\Command\Generate\Nette\GeneratedFilePrinter;
use App\Command\Generate\FileGenerator\FileGeneratorInterface;
use App\Core\Util\Files\FileGetter;
use App\Core\Util\Files\FileWriter;
use App\Core\Util\Regex;
use RuntimeException;

class GeneratedFilesCreator
{
    private readonly GeneratedFilesPrinter $printer;


    public function __construct(
        GeneratedFilePrinter $generatedFilePrinter,
        private readonly bool $overwriteExisting = false
    ) {
        $this->printer = new GeneratedFilesPrinter($generatedFilePrinter);
    }

    public function createFile(FileGeneratorInterface $phpFile): void
    {
        $filepath = self::getFilepath($phpFile);

        if (false === $this->overwriteExisting && FileGetter::doesFileExist($filepath)) {
            throw new RuntimeException(sprintf('Couldn\'t generate %s file by %s. File already exists', $filepath, static::class));
        }

        $directory = Regex::getOnlyRegexMatch($filepath, '#(.*)\/.*?\..*?$#');
        do {
            FileWriter::assureDirectoryExists($directory);
            $directory = Regex::getOnlyRegexMatch($directory, '#(.*)\/.*?$#');
        } while (str_contains('/', $directory));

        $fileContent = $this->printer->getFileContent($phpFile);
        $fileContent = self::transformContent($fileContent);

        file_put_contents(
            $filepath,
            $fileContent
        );
    }

    private static function getFilepath(FileGeneratorInterface $generatedFile): string
    {
        $namespace = self::removePrefix($generatedFile->getFile()->getNamespace(), 'App\\');

        $namespace = str_replace('\\', '/', $namespace);

        return sprintf('src/%s/%s.%s', $namespace, $generatedFile->getFile()->getClassname(), $generatedFile->getFile()->getExtension());
    }

    private static function removePrefix(string $string, string $prefix): string
    {
        if (str_starts_with($string, $prefix)) {
            return substr($string, strlen($prefix));
        } else {
            return $string;
        }
    }

    private static function transformContent(string $content): string
    {
        return $content;
    }
}
