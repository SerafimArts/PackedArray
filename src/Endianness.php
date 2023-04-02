<?php

declare(strict_types=1);

namespace Serafim\PackedArray;

enum Endianness
{
    case BIG;
    case LITTLE;

    /**
     * @psalm-suppress all
     */
    public static function auto(): self
    {
        static $endianness = null;
        return $endianness ??= (\unpack('S', "\x01\x00")[1] === 1 ? self::LITTLE : self::BIG);
    }
}
