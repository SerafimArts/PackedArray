<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Serafim\PackedArray\Endianness;
use Serafim\PackedArray\Exception\OffsetRangeException;
use Serafim\PackedArray\Exception\OffsetTypeException;
use Serafim\PackedArray\Exception\ValueRangeException;
use Serafim\PackedArray\Float32Array;
use Serafim\PackedArray\Float64Array;
use Serafim\PackedArray\Int16Array;
use Serafim\PackedArray\Int24Array;
use Serafim\PackedArray\Int32Array;
use Serafim\PackedArray\Int40Array;
use Serafim\PackedArray\Int48Array;
use Serafim\PackedArray\Int64Array;
use Serafim\PackedArray\Int8Array;
use Serafim\PackedArray\IntArrayInterface;
use Serafim\PackedArray\TypedArray;
use Serafim\PackedArray\UInt16Array;
use Serafim\PackedArray\UInt24Array;
use Serafim\PackedArray\UInt32Array;
use Serafim\PackedArray\UInt40Array;
use Serafim\PackedArray\UInt48Array;
use Serafim\PackedArray\UInt8Array;

#[Group('unit'), Group('packed-array')]
final class ReadWriteTest extends TestCase
{
    public static function arraysDataProvider(): array
    {
        $result = [
            Int8Array::class          => [Int8Array::new(1)],
            UInt8Array::class         => [UInt8Array::new(1)],

            Int16Array::class         => [Int16Array::new(1)],
            UInt16Array::class        => [UInt16Array::new(1)],
            UInt16Array::class . 'BE' => [UInt16Array::new(1, Endianness::BIG)],
            UInt16Array::class . 'LE' => [UInt16Array::new(1, Endianness::LITTLE)],

            Int24Array::class         => [Int24Array::new(1)],
            UInt24Array::class        => [UInt24Array::new(1)],
            UInt24Array::class . 'BE' => [UInt24Array::new(1, Endianness::BIG)],
            UInt24Array::class . 'LE' => [UInt24Array::new(1, Endianness::LITTLE)],

            Int32Array::class         => [Int32Array::new(1)],
            UInt32Array::class        => [UInt32Array::new(1)],
            UInt32Array::class . 'BE' => [UInt32Array::new(1, Endianness::BIG)],
            UInt32Array::class . 'LE' => [UInt32Array::new(1, Endianness::LITTLE)],

            Float32Array::class        => [Float32Array::new(1)],
            Float32Array::class . 'BE' => [Float32Array::new(1, Endianness::BIG)],
            Float32Array::class . 'LE' => [Float32Array::new(1, Endianness::LITTLE)],

            Float64Array::class        => [Float64Array::new(1)],
            Float64Array::class . 'BE' => [Float64Array::new(1, Endianness::BIG)],
            Float64Array::class . 'LE' => [Float64Array::new(1, Endianness::LITTLE)],
        ];

        if (\PHP_INT_SIZE >= 8) {
            $result[Int40Array::class ] = [Int40Array::new(1)];
            $result[UInt40Array::class] = [UInt40Array::new(1)];
            $result[UInt40Array::class . 'BE'] = [UInt40Array::new(1, Endianness::BIG)];
            $result[UInt40Array::class . 'LE'] = [UInt40Array::new(1, Endianness::LITTLE)];

            $result[Int48Array::class ] = [Int48Array::new(1)];
            $result[UInt48Array::class] = [UInt48Array::new(1)];
            $result[UInt48Array::class . 'BE'] = [UInt48Array::new(1, Endianness::BIG)];
            $result[UInt48Array::class . 'LE'] = [UInt48Array::new(1, Endianness::LITTLE)];

            $result[Int64Array::class] = [Int64Array::new(1)];
        }

        return $result;
    }

    #[DataProvider('arraysDataProvider')]
    public function testOffsetUnderflow(TypedArray $array): void
    {
        $this->skipIfAssertionDisabled();

        $this->expectException(OffsetRangeException::class);
        $this->expectExceptionCode(OffsetRangeException::CODE_UNDERFLOW);
        $this->expectExceptionMessage('cannot be less than 0');

        $array = clone $array;
        $array[-1] = 42;
    }

    #[DataProvider('arraysDataProvider')]
    public function testOffsetOverflow(TypedArray $array): void
    {
        $this->skipIfAssertionDisabled();

        $this->expectException(OffsetRangeException::class);
        $this->expectExceptionCode(OffsetRangeException::CODE_OVERFLOW);
        $this->expectExceptionMessage('cannot be greater than 1');

        $array = clone $array;
        $array[1] = 42;
    }

    #[DataProvider('arraysDataProvider')]
    public function testInvalidOffsetType(TypedArray $array): void
    {
        $this->skipIfAssertionDisabled();

        $this->expectException(OffsetTypeException::class);
        $this->expectExceptionCode(OffsetTypeException::CODE_INVALID_TYPE);
        $this->expectExceptionMessage('must be of type int');

        $array = clone $array;
        $array['test'] = 42;
    }

    #[DataProvider('arraysDataProvider')]
    public function testWriteMinBound(TypedArray $array): void
    {
        $array = clone $array;

        $array[0] = $array::ELEMENT_MIN_VALUE;
        $this->assertSame($array[0], $array::ELEMENT_MIN_VALUE);
    }

    #[DataProvider('arraysDataProvider')]
    public function testWriteMaxBound(TypedArray $array): void
    {
        $array = clone $array;

        $array[0] = $array::ELEMENT_MAX_VALUE;
        $this->assertSame($array[0], $array::ELEMENT_MAX_VALUE);
    }

    #[DataProvider('arraysDataProvider')]
    public function testRandomValue(TypedArray $array): void
    {
        if (!$array instanceof IntArrayInterface) {
            $this->markTestSkipped('This test is only available for int arrays');
        }

        $array = clone $array;

        $array[0] = $value = \random_int($array::ELEMENT_MIN_VALUE, $array::ELEMENT_MAX_VALUE);
        $this->assertSame($array[0], $value);
    }

    #[DataProvider('arraysDataProvider')]
    public function testMinBoundOverflow(TypedArray $array): void
    {
        $this->skipIfAssertionDisabled();

        if (!$array instanceof IntArrayInterface) {
            $this->markTestSkipped('This test is only available for int arrays');
        }

        if ($array::ELEMENT_MIN_VALUE === \PHP_INT_MIN) {
            $this->markTestSkipped('Exactly the same error as in the case of passing an incorrect type');
        }

        $this->expectException(ValueRangeException::class);
        $this->expectExceptionMessage('Can not assign value');

        $array = clone $array;
        $array[0] = $array::ELEMENT_MIN_VALUE - 1;
    }

    #[DataProvider('arraysDataProvider')]
    public function testMaxBoundOverflow(TypedArray $array): void
    {
        $this->skipIfAssertionDisabled();

        if (!$array instanceof IntArrayInterface) {
            $this->markTestSkipped('This test is only available for int arrays');
        }

        if ($array::ELEMENT_MAX_VALUE === \PHP_INT_MAX) {
            $this->markTestSkipped('Exactly the same error as in the case of passing an incorrect type');
        }

        $this->expectException(ValueRangeException::class);
        $this->expectExceptionMessage('Can not assign value');

        $array = clone $array;
        $array[0] = $array::ELEMENT_MAX_VALUE + 1;
    }
}
