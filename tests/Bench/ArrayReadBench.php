<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Tests\Bench;

use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;
use PhpBench\Attributes\Warmup;
use Serafim\PackedArray\Endianness;
use Serafim\PackedArray\Int16Array;
use Serafim\PackedArray\Int32Array;
use Serafim\PackedArray\Int8Array;
use Serafim\PackedArray\TypedArrayInterface;
use Serafim\PackedArray\UInt16Array;
use Serafim\PackedArray\UInt32Array;
use Serafim\PackedArray\UInt8Array;

#[Revs(100_000), Warmup(2), Iterations(10)]
#[BeforeMethods('prepare')]
final class ArrayReadBench
{
    private readonly array $int;
    private readonly \SplFixedArray $fixed;
    private readonly TypedArrayInterface $int8;
    private readonly TypedArrayInterface $uint8;
    private readonly TypedArrayInterface $int16;
    private readonly TypedArrayInterface $uint16le;
    private readonly TypedArrayInterface $uint16be;
    private readonly TypedArrayInterface $int32;
    private readonly TypedArrayInterface $uint32le;
    private readonly TypedArrayInterface $uint32be;

    public function prepare(): void
    {
        $this->int = [\PHP_INT_MIN, \PHP_INT_MAX];
        $this->fixed = \SplFixedArray::fromArray($this->int);

        $this->int8 = Int8Array::new(2);
        $this->uint8 = UInt8Array::new(2);
        $this->int16 = Int16Array::new(2);
        $this->uint16le = UInt16Array::new(2, Endianness::LITTLE);
        $this->uint16be = UInt16Array::new(2, Endianness::BIG);
        $this->int32 = Int32Array::new(2);
        $this->uint32le = UInt32Array::new(2, Endianness::LITTLE);
        $this->uint32be = UInt32Array::new(2, Endianness::BIG);

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

    public function benchPackedUInt16le(): void
    {
        $min = $this->uint16le[0];
        $max = $this->uint16le[1];
    }

    public function benchPackedUInt16be(): void
    {
        $min = $this->uint16be[0];
        $max = $this->uint16be[1];
    }

    public function benchPackedInt32(): void
    {
        $min = $this->int32[0];
        $max = $this->int32[1];
    }

    public function benchPackedUInt32le(): void
    {
        $min = $this->uint32le[0];
        $max = $this->uint32le[1];
    }

    public function benchPackedUInt32be(): void
    {
        $min = $this->uint32be[0];
        $max = $this->uint32be[1];
    }
}
