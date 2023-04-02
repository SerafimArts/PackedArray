<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

/**
 * @internal This is an internal package class
 * @psalm-internal Serafim\PackedArray\Exception
 */
final class Backtrace
{
    /**
     * @return array{non-empty-string, int<1, max>}
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    public static function get(): array
    {
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

        $actual = \dirname(__DIR__, 1);

        foreach ($trace as $item) {
            if (\str_starts_with($item['file'], $actual)) {
                continue;
            }

            return [$item['file'], $item['line']];
        }

        return [$trace[0]['file'], $trace[0]['line']];
    }
}
