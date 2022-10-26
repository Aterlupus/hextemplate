<?php
declare(strict_types=1);

namespace Test\Helper;

use App\Core\Util\Set;
use App\Core\Util\StackTraceHelper;

class Debug
{
    /** @var int[] */
    private static array $counters = [];

    /** @var bool[] */
    private static array $flags = [];

    private static array $globals = [];

    /*
     * StackTrace
     */

    public static function getStackTrace(): string
    {
        return StackTraceHelper::getStackTrace();
    }

    public static function dumpStackTrace(): void
    {
        self::setDumpMaxDepth();
        var_dump(self::getStackTrace());
    }

    /*
     * var_dump
     */

    public static function setDumpMaxDepth(int $depth = 999999): void
    {
        ini_set('xdebug.var_display_max_data', (string) 99999);
        ini_set('xdebug.var_display_max_depth', (string) $depth);
        ini_set('xdebug.var_display_max_children', (string) 1000);
    }

    /*
     * Counter
     */

    public static function count($key = null): int
    {
        if (false === isset(self::$counters[$key])) {
            self::resetCount($key);
        }

        return ++self::$counters[$key];
    }

    public static function resetCount($key = null): void
    {
        self::$counters[$key] = 0;
    }

    public static function getCount($key = null): int
    {
        return self::$counters[$key] ?? 0;
    }

    public static function dumpCount($key = null, bool $increment = false): void
    {
        if ($increment) {
            var_dump(self::count($key));
        } else {
            var_dump(self::getCount($key));
        }
    }

    /*
     * Time
     */

    public static function time(): float
    {
        return microtime(true);
    }

    public static function timeChange(float &$time, bool $reset = true): float
    {
        if ($reset) {
            $change = self::time() - $time;
            $time = self::time();
            return $change;
        } else {
            return self::time() - $time;
        }
    }

    public static function dumpTimeChange(float &$time, bool $reset = true): void
    {
        $timeChange = self::timeChange($time, $reset);
        $trace = StackTraceHelper::debugBacktrace();
        //0 - debugBacktrace; 1 - dumpTimeChange; 2 - class calling "dumpTimeChange"
        $callingClass = Set::getLast(explode('\\', $trace[2]['class']));
        $callingLine = $trace[1]['line'];

        var_dump(sprintf(
            '%s line %d: %f seconds',
            $callingClass,
            $callingLine,
            $timeChange
        ));
    }

    /*
     * StackTrace Checker
     */

    public static function isUnderMethod(string $method, ?int $withLine = null): bool
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $stackElement) {
            if (false === isset($stackElement['class'])) {
                continue;
            }

            if ($stackElement['function'] === $method) {
                if (null === $withLine || $stackElement['line'] === $withLine) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function isUnderClass(string $class): bool
    {
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $stackElement) {
            if (false === isset($stackElement['class'])) {
                continue;
            }

            if ($stackElement['class'] === $class) {
                return true;
            }
        }

        return false;
    }

    public static function isUnderClassOrParent(string $class): bool
    {
        $classes = array_merge([$class], class_parents($class));

        return Set::isAny($classes, function (string $class) {
            return self::isUnderClass($class);
        });
    }

    /*
     * Flag
     */

    public static function setFlag($name = null): void
    {
        self::$flags[$name] = true;
    }

    public static function unsetFlag($name = null): void
    {
        unset(self::$flags[$name]);
    }

    public static function isFlag($name = null): bool
    {
        return true === isset(self::$flags[$name]);
    }

    public static function clearFlags(): void
    {
        self::$flags = [];
    }

    /*
     * Exec
     */

    public static function once($name = null): bool
    {
        if (false === static::isFlag($name)) {
            static::setFlag($name);
            return true;
        } else {
            return false;
        }
    }

    public static function nthTime(int $occurrence): bool
    {
        $name = md5(StackTraceHelper::getStackTrace());
        if (static::count($name) === $occurrence) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Global
     */

    public static function setGlobal($key, $value): void
    {
        static::$globals[$key] = $value;
    }

    public static function getGlobal($key)
    {
        return static::$globals[$key] ?? null;
    }

    public static function dumpGlobal($key)
    {
        return static::getGlobal($key);
    }
}
