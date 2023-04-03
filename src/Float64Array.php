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
 * @template-extends TypedArray<float<2.2250738585072014E-308, 1.7976931348623158E+308>>
 * @template-implements FloatArrayInterface<float<2.2250738585072014E-308, 1.7976931348623158E+308>>
 */
final class Float64Array extends TypedArray implements FloatArrayInterface
{
    /**
     * The constant represents the size in bytes of each element in a typed array.
     *
     * @var int<1, max>
     */
    public const ELEMENT_BYTES = 8;

    /**
     * The minimal available value of the element.
     *
     * @var float<2.2250738585072014E-308, 1.7976931348623158E+308>
     */
    public const ELEMENT_MIN_VALUE = 2.2250738585072014E-308;

    /**
     * The maximal available value of the element.
     *
     * @var float<2.2250738585072014E-308, 1.7976931348623158E+308>
     */
    public const ELEMENT_MAX_VALUE = 1.7976931348623158E+308;

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
        assert($size >= 0, ArraySizeException::fromUnderflow('Float64Array', $size));

        return self::fromBytes(\str_repeat("\0", $size * self::ELEMENT_BYTES), $endianness);
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
     * @return float<2.2250738585072014E-308, 1.7976931348623158E+308>
     *
     * @psalm-suppress MoreSpecificReturnType : The type in the docblock is more
     *                 restrictive than the type specified in the return type.
     * @psalm-suppress LessSpecificReturnStatement : Same to "MoreSpecificReturnType"
     */
    public function offsetGet(mixed $offset): float
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        return (float)(\unpack($this->endianess === Endianness::LITTLE ? 'e' : 'E', \substr($this->data, $offset, 8))[1]);
    }

    /**
     * @param int<0, max> $offset
     * @param float<2.2250738585072014E-308, 1.7976931348623158E+308> $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        assert(\is_float($value), ValueTypeException::fromInvalidType((string)$this, $value));
        assert($value >= self::ELEMENT_MIN_VALUE, ValueRangeException::fromUnderflow((string)$this, $value));
        assert($value <= self::ELEMENT_MAX_VALUE, ValueRangeException::fromOverflow((string)$this, $value));

        $bytes = \pack($this->endianess === Endianness::LITTLE ? 'e' : 'E', $value);
        $this->data[$offset] = $bytes[0];
        $this->data[$offset + 1] = $bytes[1];
        $this->data[$offset + 2] = $bytes[2];
        $this->data[$offset + 3] = $bytes[3];
        $this->data[$offset + 4] = $bytes[4];
        $this->data[$offset + 5] = $bytes[5];
        $this->data[$offset + 6] = $bytes[6];
        $this->data[$offset + 7] = $bytes[7];
    }

    /**
     * @param int<0, max> $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        assert(\is_int($offset), OffsetTypeException::fromInvalidType((string)$this, $offset));
        assert($offset >= 0, OffsetRangeException::fromUnderflow((string)$this, $offset));
        assert($offset < $this->length, OffsetRangeException::fromOverflow((string)$this, $offset, $this->length));

        $value = 0.0;
        $bytes = \pack($this->endianess === Endianness::LITTLE ? 'e' : 'E', $value);
        $this->data[$offset] = $bytes[0];
        $this->data[$offset + 1] = $bytes[1];
        $this->data[$offset + 2] = $bytes[2];
        $this->data[$offset + 3] = $bytes[3];
        $this->data[$offset + 4] = $bytes[4];
        $this->data[$offset + 5] = $bytes[5];
        $this->data[$offset + 6] = $bytes[6];
        $this->data[$offset + 7] = $bytes[7];
    }

    /**
     * @return \Traversable<int<0, max>, float<2.2250738585072014E-308, 1.7976931348623158E+308>>
     *
     * @psalm-suppress MoreSpecificReturnType : The type in the docblock is more
     *                 restrictive than the type specified in method's body.
     * @psalm-suppress LessSpecificReturnStatement : Same to "MoreSpecificReturnType"
     */
    public function getIterator(): \Traversable
    {
        for ($offset = 0; $offset < $this->bytes; $offset += 8) {
            yield $offset => (float)(\unpack($this->endianess === Endianness::LITTLE ? 'e' : 'E', \substr($this->data, $offset, 8))[1]);
        }
    }
}
