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
final class ArrayWriteBench
{
    private array $int;
    private readonly \SplFixedArray $fixed;
    private readonly TypedArrayInterface $int8;
    private readonly TypedArrayInterface $uint8;
    private readonly TypedArrayInterface $int16;
    private readonly TypedArrayInterface $uint16le;
    private readonly TypedArrayInterface $uint16be;

    public function prepare(): void
    {
        $this->int = [0, 0];
        $this->fixed = \SplFixedArray::fromArray($this->int);

        $this->int8 = Int8Array::new(2);
        $this->uint8 = UInt8Array::new(2);
        $this->int16 = Int16Array::new(2);
        $this->uint16le = UInt16Array::new(2, Endianness::LITTLE);
        $this->uint16be = UInt16Array::new(2, Endianness::BIG);
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

    public function benchPackedUInt16le(): void
    {
        $this->uint16le[0] = 0;
        $this->uint16le[1] = 65535;
    }

    public function benchPackedUInt16be(): void
    {
        $this->uint16be[0] = 0;
        $this->uint16be[1] = 65535;
    }

    public function benchPackedInt32(): void
    {
        $this->int32[0] = -2147483648;
        $this->int32[1] = 2147483647;
    }

    public function benchPackedUInt32le(): void
    {
        $this->uint32le[0] = 0;
        $this->uint32le[1] = 4294967295;
    }

    public function benchPackedUInt32be(): void
    {
        $this->uint32be[0] = 0;
        $this->uint32be[1] = 4294967295;
    }
}
