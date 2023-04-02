<?php

declare(strict_types=1);

namespace Serafim\PackedArray;

/**
 * @template TValue of scalar
 *
 * @template-implements TypedArrayInterface<TValue>
 * @template-implements \IteratorAggregate<int<0, max>, TValue>
 */
abstract class TypedArray implements TypedArrayInterface, \IteratorAggregate, \Stringable
{
    /**
     * Array size in elements.
     *
     * @var int<0, max>
     */
    public readonly int $length;

    /**
     * Array size in bytes.
     *
     * @var int<0, max>
     */
    public readonly int $bytes;

    /**
     * Fixed byte array representation.
     *
     * @readonly Changing this data outside the class is not allowed.
     * @psalm-readonly-allow-private-mutation
     */
    public string $data;

    /**
     * @param string $data Compressed byte representation of an array.
     * @param int<1, max> $bytesPerElement The number of bytes allocated within one element.
     */
    protected function __construct(
        string $data,
        public readonly int $bytesPerElement,
    ) {
        // Pitch fold compensation
        if ($expected = (\strlen($data) % $this->bytesPerElement)) {
            $this->data = $data . \str_repeat("\0", $expected);
        } else {
            $this->data = $data;
        }

        $this->bytes = \strlen($this->data);

        /** @psalm-suppress PropertyTypeCoercion */
        $this->length = (int)($this->bytes / $this->bytesPerElement);
    }

    /**
     * Creates a new typed array object from the given byte string.
     */
    abstract public static function fromBytes(string $bytes): self;

    /**
     * Creates a new typed array object of the given size.
     *
     * @param int<0, max> $size
     */
    abstract public static function new(int $size): self;

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return $this->length;
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        $fqn = \explode('\\', static::class);

        return \end($fqn) . '[' . $this->length . ']';
    }
}
