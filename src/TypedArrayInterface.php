<?php

declare(strict_types=1);

namespace Serafim\PackedArray;

/**
 * @template TValue of scalar
 *
 * @template-extends \Traversable<int<0, max>, TValue>
 * @template-extends \ArrayAccess<int<0, max>, TValue>
 */
interface TypedArrayInterface extends \Traversable, \Countable, \ArrayAccess
{
    /**
     * The constant represents the size in bytes of each element in a
     * typed array.
     *
     * Warning, this value MUST be overridden by the implementation and
     * is used for autocompletion and static analysis!
     *
     * @var int<1, max>
     */
    public const ELEMENT_BYTES = 1;

    /**
     * The minimal available value of the element.
     *
     * Warning, this value MUST be overridden by the implementation and
     * is used for autocompletion and static analysis!
     *
     * @var int<min, max>
     */
    public const ELEMENT_MIN_VALUE = 0;

    /**
     * The maximal available value of the element.
     *
     * Warning, this value MUST be overridden by the implementation and
     * is used for autocompletion and static analysis!
     *
     * @var int<min, max>
     */
    public const ELEMENT_MAX_VALUE = 0;
}
