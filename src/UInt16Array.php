<?php

declare(strict_types=1);

namespace Serafim\PackedArray;

use Serafim\PackedArray\Exception\ArraySizeException;
use Serafim\PackedArray\Exception\OffsetRangeException;
use Serafim\PackedArray\Exception\OffsetTypeException;
use Serafim\PackedArray\Exception\ValueRangeException;
use Serafim\PackedArray\Exception\ValueTypeException;

/**
 * @generated Please note that this class has been generated.
 *
 * @template-extends TypedArray<int<0, 65535>>
 */
final class UInt16Array extends TypedArray
{
    /**
     * The constant represents the size in bytes of each element in a typed array.
     *
     * @var int<1, max>
     */
    public const ELEMENT_BYTES = 2;

    /**
     * The minimal available value of the element.
     *
     * @var int<0, 65535>
     */
    public const ELEMENT_MIN_VALUE = 0;

    /**
     * The maximal available value of the element.
     *
     * @var int<0, 65535>
     */
    public const ELEMENT_MAX_VALUE = 65535;

    public readonly Endianness $endianness;

    public function __construct(
        string $bytes,
        Endianness $endianness = null,
    ) {
        $this->endianness = $endianness ?? Endianness::auto();

        parent::__construct($bytes, self::ELEMENT_BYTES);
    }

    public static function fromBytes(
        string $bytes,
        Endianness $endianness = null,
    ): self {
        return new self($bytes, $endianness);
    }

    public static function new(
        int $size,
        Endianness $endianness = null,
    ): self {
        assert($size >= 0, ArraySizeException::fromUnderflow('UInt16Array', $size));

        return self::fromBytes(\str_repeat("\0", $size * self::ELEMENT_BYTES), $endianness);
    }

    public function offsetExists(mixed $offset): bool
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));

        return $offset >= 0 && $offset < $this->length;
    }

    public function offsetGet(mixed $offset): int
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        return $this->endianness === Endianness::LITTLE
                ? \ord($this->data[$offset]) | \ord($this->data[$offset + 1]) << 8
                : \ord($this->data[$offset + 1]) | \ord($this->data[$offset]) << 8;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        assert(\is_int($value), ValueTypeException::fromInvalidType((string)$this, $value));
        assert($value >= 0, ValueRangeException::fromUnderflow((string)$this, $value));
        assert($value <= 65535, ValueRangeException::fromOverflow((string)$this, $value));

        if ($this->endianness === Endianness::LITTLE) {
                $this->data[$offset] = \chr($value);
                $this->data[$offset + 1] = \chr($value >> 8);
            } else {
                $this->data[$offset] = \chr($value >> 8);
                $this->data[$offset + 1] = \chr($value);
            }
    }

    public function offsetUnset(mixed $offset): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        $value = 0;
        if ($this->endianness === Endianness::LITTLE) {
                $this->data[$offset] = \chr($value);
                $this->data[$offset + 1] = \chr($value >> 8);
            } else {
                $this->data[$offset] = \chr($value >> 8);
                $this->data[$offset + 1] = \chr($value);
            }
    }

    public function getIterator(): \Traversable
    {
        for ($offset = 0; $offset < $this->bytes; $offset += 2) {
            yield $offset => $this->endianness === Endianness::LITTLE
                ? \ord($this->data[$offset]) | \ord($this->data[$offset + 1]) << 8
                : \ord($this->data[$offset + 1]) | \ord($this->data[$offset]) << 8;
        }
    }
}
