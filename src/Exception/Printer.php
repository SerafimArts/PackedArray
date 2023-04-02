<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

/**
 * @internal This is an internal package class
 * @psalm-internal Serafim\PackedArray\Exception
 */
final class Printer
{
    public static function type(mixed $value): string
    {
        return \get_debug_type($value);
    }

    public static function value(mixed $value): string
    {
        $type = self::type($value);

        if (\is_scalar($value)) {
            $scalar = match (true) {
                \is_int($value) => self::formatInt($value),
                \is_float($value) => self::formatFloat($value),
                \is_string($value) => self::formatString($value),
                \is_bool($value) => self::formatBool($value),
                default => '<unknown>',
            };

            return $type . '(' . $scalar . ')';
        }

        if (\is_resource($value)) {
            return $type . '(' . \get_resource_type($value) . ')';
        }

        return $type;
    }

    /**
     * @return non-empty-string
     */
    private static function formatBool(bool $value): string
    {
        return $value ? 'true' : 'false';
    }

    /**
     * @return non-empty-string
     */
    private static function formatString(string $value): string
    {
        if (\strlen($value) > 16) {
            $value = \substr($value, 0, 16) . '…';
        }

        return '"' . $value . '"';
    }

    /**
     * @return non-empty-string
     */
    private static function formatFloat(float $value): string
    {
        return \number_format($value, 2, '.', '');
    }

    /**
     * @return non-empty-string
     */
    private static function formatInt(int $value): string
    {
        return \number_format($value, thousands_separator: '_');
    }
}
