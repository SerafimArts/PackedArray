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
 * @template-extends TypedArray<int<-32768, 32767>>
 */
final class Int16Array extends TypedArray
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
     * @var int<-32768, 32767>
     */
    public const ELEMENT_MIN_VALUE = -32768;

    /**
     * The maximal available value of the element.
     *
     * @var int<-32768, 32767>
     */
    public const ELEMENT_MAX_VALUE = 32767;

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
        assert($size >= 0, ArraySizeException::fromUnderflow('Int16Array', $size));

        return self::fromBytes(\str_repeat("\0", $size * self::ELEMENT_BYTES));
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

        return ($lo = \ord($this->data[$offset + 1])) & 0x80
                ? (\ord($this->data[$offset]) | $lo << 8) - 0x10000
                : \ord($this->data[$offset]) | $lo << 8;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        assert(\is_int($value), ValueTypeException::fromInvalidType((string)$this, $value));
        assert($value >= -32768, ValueRangeException::fromUnderflow((string)$this, $value));
        assert($value <= 32767, ValueRangeException::fromOverflow((string)$this, $value));

        $this->data[$offset] = \chr($value);
        $this->data[$offset + 1] = \chr($value >> 8);
    }

    public function offsetUnset(mixed $offset): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        $value = 0;
        $this->data[$offset] = \chr($value);
        $this->data[$offset + 1] = \chr($value >> 8);
    }

    public function getIterator(): \Traversable
    {
        for ($offset = 0; $offset < $this->bytes; $offset += 2) {
            yield $offset => ($lo = \ord($this->data[$offset + 1])) & 0x80
                ? (\ord($this->data[$offset]) | $lo << 8) - 0x10000
                : \ord($this->data[$offset]) | $lo << 8;
        }
    }
}
