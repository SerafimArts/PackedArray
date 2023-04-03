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
 * @template-extends TypedArray<int<-2147483648, 2147483647>>
 */
final class Int32Array extends TypedArray
{
    /**
     * The constant represents the size in bytes of each element in a typed array.
     *
     * @var int<1, max>
     */
    public const ELEMENT_BYTES = 4;

    /**
     * The minimal available value of the element.
     *
     * @var int<-2147483648, 2147483647>
     */
    public const ELEMENT_MIN_VALUE = -2147483647-1;

    /**
     * The maximal available value of the element.
     *
     * @var int<-2147483648, 2147483647>
     */
    public const ELEMENT_MAX_VALUE = 2147483647;

    public function __construct(string $bytes)
    {
        parent::__construct($bytes, self::ELEMENT_BYTES);
    }

    public static function fromBytes(string $bytes): self
    {
        return new self($bytes);
    }

    public static function new(int $size): self
    {
        assert($size >= 0, ArraySizeException::fromUnderflow('Int32Array', $size));

        return self::fromBytes(\str_repeat("\0", $size * self::ELEMENT_BYTES));
    }

    /**
     * @param int<0, max> $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));

        return $offset >= 0 && $offset < $this->length;
    }

    /**
     * @param int<0, max> $offset
     *
     * @return int<-2147483648, 2147483647>
     *
     * @psalm-suppress MoreSpecificReturnType : The type in the docblock is more
     *                 restrictive than the type specified in the return type.
     * @psalm-suppress LessSpecificReturnStatement : Same to "MoreSpecificReturnType"
     */
    public function offsetGet(mixed $offset): int
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        return (int)(\unpack('l', \substr($this->data, $offset, 4))[1]);
    }

    /**
     * @param int<0, max> $offset
     * @param int<-2147483648, 2147483647> $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        assert(\is_int($value), ValueTypeException::fromInvalidType((string)$this, $value));
        assert($value >= self::ELEMENT_MIN_VALUE, ValueRangeException::fromUnderflow((string)$this, $value));
        assert($value <= self::ELEMENT_MAX_VALUE, ValueRangeException::fromOverflow((string)$this, $value));

        $this->data[$offset] = \chr($value);
        $this->data[$offset + 1] = \chr($value >> 8);
        $this->data[$offset + 2] = \chr($value >> 16);
        $this->data[$offset + 3] = \chr($value >> 24);
    }

    /**
     * @param int<0, max> $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        $value = 0;
        $this->data[$offset] = \chr($value);
        $this->data[$offset + 1] = \chr($value >> 8);
        $this->data[$offset + 2] = \chr($value >> 16);
        $this->data[$offset + 3] = \chr($value >> 24);
    }

    /**
     * @return \Traversable<int<0, max>, int<-2147483648, 2147483647>>
     *
     * @psalm-suppress MoreSpecificReturnType : The type in the docblock is more
     *                 restrictive than the type specified in method's body.
     * @psalm-suppress LessSpecificReturnStatement : Same to "MoreSpecificReturnType"
     */
    public function getIterator(): \Traversable
    {
        for ($offset = 0; $offset < $this->bytes; $offset += 4) {
            yield $offset => (int)(\unpack('l', \substr($this->data, $offset, 4))[1]);
        }
    }
}
