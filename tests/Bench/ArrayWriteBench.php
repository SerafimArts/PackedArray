<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Tests\Bench;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use Serafim\PackedArray\Endianness;
use Serafim\PackedArray\Float32Array;
use Serafim\PackedArray\Float64Array;
use Serafim\PackedArray\Int16Array;
use Serafim\PackedArray\Int32Array;
use Serafim\PackedArray\Int64Array;
use Serafim\PackedArray\Int8Array;
use Serafim\PackedArray\TypedArrayInterface;
use Serafim\PackedArray\UInt16Array;
use Serafim\PackedArray\UInt32Array;
use Serafim\PackedArray\UInt8Array;

#[Revs(10_000), Warmup(2), Iterations(5)]
#[BeforeMethods('prepare')]
final class ArrayWriteBench
{
    private array $int;
    private readonly \SplFixedArray $fixed;
    private readonly TypedArrayInterface $int8;
    private readonly TypedArrayInterface $uint8;
    private readonly TypedArrayInterface $int16;
    private readonly TypedArrayInterface $uint16;
    private readonly TypedArrayInterface $int32;
    private readonly TypedArrayInterface $uint32;
    private readonly TypedArrayInterface $int64;
    private readonly TypedArrayInterface $float32;
    private readonly TypedArrayInterface $float64;

    public function prepare(): void
    {
        $this->int = [0, 0];
        $this->fixed = \SplFixedArray::fromArray($this->int);

        $this->int8 = Int8Array::new(2);
        $this->uint8 = UInt8Array::new(2);
        $this->int16 = Int16Array::new(2);
        $this->uint16 = UInt16Array::new(2);
        $this->int32 = Int32Array::new(2);
        $this->uint32 = UInt32Array::new(2);
        $this->int64 = Int64Array::new(2);
        $this->float32 = Float32Array::new(2);
        $this->float64 = Float64Array::new(2);
    }

    public function benchNative(): void
    {
        $this->int[0] = -2147483648;
        $this->int[1] = 2147483647;
    }

    public function benchSplFixedArray(): void
    {
        $this->fixed[0] = -2147483648;
        $this->fixed[1] = 2147483647;
    }

    public function benchPackedInt8(): void
    {
        $this->int8[0] = -128;
        $this->int8[1] = 127;
    }

    public function benchPackedUInt8(): void
    {
        $this->uint8[0] = 0;
        $this->uint8[1] = 255;
    }

    public function benchPackedInt16(): void
    {
        $this->int16[0] = -32768;
        $this->int16[1] = 32767;
    }

    public function benchPackedUInt16(): void
    {
        $this->uint16[0] = 0;
        $this->uint16[1] = 65535;
    }

    public function benchPackedInt32(): void
    {
        $this->int32[0] = -2147483648;
        $this->int32[1] = 2147483647;
    }

    public function benchPackedUInt32(): void
    {
        $this->uint32[0] = 0;
        $this->uint32[1] = 4294967295;
    }

    public function benchPackedInt64(): void
    {
        $this->int64[0] = \PHP_INT_MIN;
        $this->int64[1] = \PHP_INT_MAX;
    }

    public function benchPackedFloat32(): void
    {
        $this->float32[0] = Float32Array::ELEMENT_MIN_VALUE;
        $this->float32[1] = Float32Array::ELEMENT_MAX_VALUE;
    }

    public function benchPackedFloat64(): void
    {
        $this->float64[0] = \PHP_FLOAT_MIN;
        $this->float64[1] = \PHP_FLOAT_MAX;
    }
}
