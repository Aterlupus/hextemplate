<?php
declare(strict_types=1);

namespace App\Core\Util;

use App\Core\Format\StackTraceFormatter;

class StackTraceHelper
{
    private const DEBUG_METHODS = [
        'debugBacktrace',
        'getStackTrace',
    ];

    public static function getStackTraceMessage(): string
    {
        return "StackTrace:\n" . self::getStackTrace();
    }

    public static function getStackTrace(string $delimiter = "\n", bool $pruneDebug = true): string
    {
        return StackTraceFormatter::format(self::debugBacktrace($pruneDebug), $delimiter);
    }

    public static function debugBacktrace(bool $pruneDebug = true): array
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        if ($pruneDebug) {
            return self::pruneDebugFromBacktrace($backtrace);
        } else {
            return $backtrace;
        }
    }

    private static function pruneDebugFromBacktrace(array $backtrace): array
    {
        foreach ($backtrace as $index => $row) {
            if (array_key_exists('class', $row) && in_array($row['function'], self::DEBUG_METHODS) ) {
                unset($backtrace[$index]);
            }
        }

        return array_values($backtrace);
    }
}
