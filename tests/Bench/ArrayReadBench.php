<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Tests\Bench;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
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
final class ArrayReadBench
{
    private readonly array $int;
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
        $this->int = [\PHP_INT_MIN, \PHP_INT_MAX];
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

        // Init min/max bounds
        foreach (\get_object_vars($this) as $value) {
            if ($value instanceof TypedArrayInterface) {
                $value[0] = $value::ELEMENT_MIN_VALUE;
                $value[1] = $value::ELEMENT_MAX_VALUE;
            }
        }
    }

    public function benchNative(): void
    {
        $min = $this->int[0];
        $max = $this->int[1];
    }

    public function benchSplFixedArray(): void
    {
        $min = $this->fixed[0];
        $max = $this->fixed[1];
    }

    public function benchPackedInt8(): void
    {
        $min = $this->int8[0];
        $max = $this->int8[1];
    }

    public function benchPackedUInt8(): void
    {
        $min = $this->uint8[0];
        $max = $this->uint8[1];
    }

    public function benchPackedInt16(): void
    {
        $min = $this->int16[0];
        $max = $this->int16[1];
    }

    public function benchPackedUInt16(): void
    {
        $min = $this->uint16[0];
        $max = $this->uint16[1];
    }

    public function benchPackedInt32(): void
    {
        $min = $this->int32[0];
        $max = $this->int32[1];
    }

    public function benchPackedUInt32(): void
    {
        $min = $this->uint32[0];
        $max = $this->uint32[1];
    }

    public function benchPackedInt64(): void
    {
        $min = $this->int64[0];
        $max = $this->int64[1];
    }

    public function benchPackedFloat32(): void
    {
        $min = $this->float32[0];
        $max = $this->float32[1];
    }

    public function benchPackedFloat64(): void
    {
        $min = $this->float64[0];
        $max = $this->float64[1];
    }
}
