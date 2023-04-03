<?php

declare(strict_types=1);

namespace Serafim\PackedArray;

/**
 * @template TValue of float
 *
 * @template-extends TypedArrayInterface<float>
 */
interface FloatArrayInterface extends TypedArrayInterface
{
    /**
     * @param int<0, max> $offset
     *
     * @return TValue
     */
    public function offsetGet(mixed $offset): float;
}
