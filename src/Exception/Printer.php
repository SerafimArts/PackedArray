<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

/**
 * @internal This is an internal package class
 * @psalm-internal Serafim\PackedArray\Exception
 */
final class Printer
{
    /**
     * @return non-empty-string
     */
    public static function type(mixed $value): string
    {
        return \get_debug_type($value);
    }

    /**
     * @return non-empty-string
     */
    public static function value(mixed $value): string
    {
        $result = self::type($value);

        $suffix = match (true) {
            \is_int($value) => self::formatInt($value),
            \is_float($value) => self::formatFloat($value),
            \is_string($value) => self::formatString($value),
            \is_bool($value) => self::formatBool($value),
            \is_resource($value) => \get_resource_type($value),
            default => null,
        };

        if ($suffix !== null) {
            $result .= '(' . $suffix . ')';
        }

        return $result;
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
     * @psalm-suppress MoreSpecificReturnType : The number_format always return non-empty-string
     * @psalm-suppress LessSpecificReturnStatement : The number_format always return non-empty-string
     */
    private static function formatFloat(float $value): string
    {
        return \number_format($value, 2, '.', '');
    }

    /**
     * @return non-empty-string
     * @psalm-suppress MoreSpecificReturnType : The number_format always return non-empty-string
     * @psalm-suppress LessSpecificReturnStatement : The number_format always return non-empty-string
     */
    private static function formatInt(int $value): string
    {
        return \number_format($value, thousands_separator: '_');
    }
}
