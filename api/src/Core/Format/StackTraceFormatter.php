<?php
declare(strict_types=1);

namespace App\Core\Format;

class StackTraceFormatter
{
    public static function format(array $stackTrace, string $delimiter = "\n"): string
    {
        return array_reduce(
            $stackTrace,
            fn($traceString, $stackRow) => $traceString . self::formatStackRow($stackRow) . $delimiter,
        );
    }

    private static function formatStackRow(array $stackRow): string
    {
        return sprintf(
            '%s %s %s',
            $stackRow['class'] ?? '[no class]',
            $stackRow['function'],
            $stackRow['line'] ?? '[no line]'
        );
    }
}
