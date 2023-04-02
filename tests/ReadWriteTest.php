<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Serafim\PackedArray\Endianness;
use Serafim\PackedArray\Exception\OffsetRangeException;
use Serafim\PackedArray\Exception\OffsetTypeException;
use Serafim\PackedArray\Exception\ValueRangeException;
use Serafim\PackedArray\Int16Array;
use Serafim\PackedArray\Int8Array;
use Serafim\PackedArray\TypedArray;
use Serafim\PackedArray\UInt16Array;
use Serafim\PackedArray\UInt8Array;

#[Group('unit'), Group('packed-array')]
final class ReadWriteTest extends TestCase
{
    public static function arraysDataProvider(): array
    {
        return [
            Int8Array::class          => [Int8Array::new(1)],
            UInt8Array::class         => [UInt8Array::new(1)],
            Int16Array::class         => [Int16Array::new(1)],
            UInt16Array::class        => [UInt16Array::new(1)],
            UInt16Array::class . 'BE' => [UInt16Array::new(1, Endianness::BIG)],
            UInt16Array::class . 'LE' => [UInt16Array::new(1, Endianness::LITTLE)],
        ];
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
        $array = clone $array;

        $value = match(true) {
            \is_int($array[0]) => \random_int($array::ELEMENT_MIN_VALUE, $array::ELEMENT_MAX_VALUE),
            \is_float($array[0]) => \random_int(
                (int)($array::ELEMENT_MIN_VALUE * 1000),
                (int)($array::ELEMENT_MAX_VALUE * 1000),
            ) / 1000
        };

        $array[0] = $value;
        $this->assertSame($array[0], $value);
    }

    #[DataProvider('arraysDataProvider')]
    public function testMinBoundOverflow(TypedArray $array): void
    {
        $this->skipIfAssertionDisabled();

        $this->expectException(ValueRangeException::class);
        $this->expectExceptionMessage('Can not assign value');

        $array = clone $array;
        $array[0] = $array::ELEMENT_MIN_VALUE - 1;
    }

    #[DataProvider('arraysDataProvider')]
    public function testMaxBoundOverflow(TypedArray $array): void
    {
        $this->skipIfAssertionDisabled();

        $this->expectException(ValueRangeException::class);
        $this->expectExceptionMessage('Can not assign value');

        $array = clone $array;
        $array[0] = $array::ELEMENT_MAX_VALUE + 1;
    }
}
