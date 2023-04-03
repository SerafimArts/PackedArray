<?php

declare(strict_types=1);

namespace Serafim\PackedArray;

/**
 * @template TValue of int
 *
 * @template-extends TypedArrayInterface<int>
 */
interface IntArrayInterface extends TypedArrayInterface
{
    /**
     * @param int<0, max> $offset
     *
     * @return TValue
     */
    public function offsetGet(mixed $offset): int;
}
