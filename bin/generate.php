<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/generate/Sample.php';

$twig = new Environment(new FilesystemLoader(__DIR__ . '/generate'));

$samples = [
    // i8
    new Sample(
        class: 'Int8Array',
        type: 'int',
        default: '0',
        from: -128,
        to: 127,
        bytesPerElement: 1,
        unpack: <<<'PHP'
            ($value = \ord($this->data[$offset])) & 0x80 ? $value - 0x100 : $value;
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            PHP,
    ),
    new Sample(
        class: 'UInt8Array',
        type: 'int',
        default: '0',
        from: 0,
        to: 255,
        bytesPerElement: 1,
        unpack: <<<'PHP'
            \ord($this->data[$offset]);
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            PHP,
    ),
    // i16
    new Sample(
        class: 'Int16Array',
        type: 'int',
        default: '0',
        from: -32768,
        to: 32767,
        bytesPerElement: 2,
        unpack: <<<'PHP'
            ($lo = \ord($this->data[$offset + 1])) & 0x80
                ? (\ord($this->data[$offset]) | $lo << 8) - 0x0001_0000
                : (\ord($this->data[$offset]) | $lo << 8);
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            $this->data[$offset + 1] = \chr($value >> 8);
            PHP,
    ),
    new Sample(
        class: 'UInt16Array',
        type: 'int',
        default: '0',
        from: 0,
        to: 65535,
        bytesPerElement: 2,
        unpack: <<<'PHP'
            $this->endianness === Endianness::LITTLE
                ? \ord($this->data[$offset]) | \ord($this->data[$offset + 1]) << 8
                : \ord($this->data[$offset + 1]) | \ord($this->data[$offset]) << 8;
            PHP,
        pack: <<<'PHP'
            if ($this->endianness === Endianness::LITTLE) {
                $this->data[$offset] = \chr($value);
                $this->data[$offset + 1] = \chr($value >> 8);
            } else {
                $this->data[$offset] = \chr($value >> 8);
                $this->data[$offset + 1] = \chr($value);
            }
            PHP,
        endianness: true,
    ),
    // i24
    new Sample(
        class: 'Int24Array',
        type: 'int',
        default: '0',
        from: -8_388_608,
        to: 8_388_607,
        bytesPerElement: 3,
        unpack: <<<'PHP'
            ($lo = \ord($this->data[$offset + 2])) & 0x80
                ? (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | $lo << 16
                  ) - 0x0100_0000
                : (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | $lo << 16
                  );
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            $this->data[$offset + 1] = \chr($value >> 8);
            $this->data[$offset + 2] = \chr($value >> 16);
            PHP,
    ),
    new Sample(
        class: 'UInt24Array',
        type: 'int',
        default: '0',
        from: 0,
        to: 16_777_215,
        bytesPerElement: 3,
        unpack: <<<'PHP'
            $this->endianness === Endianness::LITTLE
                ? (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  )
                : (\ord($this->data[$offset + 2])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset]) << 16
                  );
            PHP,
        pack: <<<'PHP'
            if ($this->endianness === Endianness::LITTLE) {
                $this->data[$offset] = \chr($value);
                $this->data[$offset + 1] = \chr($value >> 8);
                $this->data[$offset + 2] = \chr($value >> 16);
            } else {
                $this->data[$offset] = \chr($value >> 16);
                $this->data[$offset + 1] = \chr($value >> 8);
                $this->data[$offset + 2] = \chr($value);
            }
            PHP,
        endianness: true,
    ),
    // i32
    new Sample(
        class: 'Int32Array',
        type: 'int',
        default: '0',
        // Avoid int32 overflow on x86 platforms
        from: '-2147483647-1',
        to: 2_147_483_647,
        bytesPerElement: 4,
        unpack: <<<'PHP'
            (int)(\unpack('l', \substr($this->data, $offset, 4))[1]);
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            $this->data[$offset + 1] = \chr($value >> 8);
            $this->data[$offset + 2] = \chr($value >> 16);
            $this->data[$offset + 3] = \chr($value >> 24);
            PHP,
        fromDocBlock: '-2147483648'
    ),
    new Sample(
        class: 'UInt32Array',
        type: 'int',
        default: '0',
        from: 0,
        to: 4_294_967_295,
        bytesPerElement: 4,
        unpack: <<<'PHP'
            (int)(\unpack($this->endianness === Endianness::LITTLE ? 'V' : 'N', \substr($this->data, $offset, 4))[1]);
            PHP,
        pack: <<<'PHP'
            if ($this->endianness === Endianness::LITTLE) {
                $this->data[$offset] = \chr($value);
                $this->data[$offset + 1] = \chr($value >> 8);
                $this->data[$offset + 2] = \chr($value >> 16);
                $this->data[$offset + 3] = \chr($value >> 24);
            } else {
                $this->data[$offset] = \chr($value >> 24);
                $this->data[$offset + 1] = \chr($value >> 16);
                $this->data[$offset + 2] = \chr($value >> 8);
                $this->data[$offset + 3] = \chr($value);
            }
            PHP,
        endianness: true,
    ),
    // i40
    new Sample(
        class: 'Int40Array',
        type: 'int',
        default: '0',
        from: '-549755813887-1',
        to: 549_755_813_887,
        bytesPerElement: 5,
        unpack: <<<'PHP'
            ($lo = \ord($this->data[$offset + 4])) & 0x80
                ? (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  | \ord($this->data[$offset + 3]) << 24
                  | $lo << 32
                  ) - 0x0100_0000_0000
                : (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  | \ord($this->data[$offset + 3]) << 24
                  | $lo << 32
                  );
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            $this->data[$offset + 1] = \chr($value >> 8);
            $this->data[$offset + 2] = \chr($value >> 16);
            $this->data[$offset + 3] = \chr($value >> 24);
            $this->data[$offset + 4] = \chr($value >> 32);
            PHP,
        precondition: <<<'PHP'
            if (\PHP_INT_SIZE < self::ELEMENT_BYTES) {
                throw new \LogicException('The current platform does not support int40 arrays');
            }
            PHP,
        fromDocBlock: '-549755813888'
    ),
    new Sample(
        class: 'UInt40Array',
        type: 'int',
        default: '0',
        from: 0,
        to: 1_099_511_627_775,
        bytesPerElement: 5,
        unpack: <<<'PHP'
            $this->endianness === Endianness::LITTLE
                ? (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  | \ord($this->data[$offset + 3]) << 24
                  | \ord($this->data[$offset + 4]) << 32
                  )
                : (\ord($this->data[$offset + 4])
                  | \ord($this->data[$offset + 3]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  | \ord($this->data[$offset + 1]) << 24
                  | \ord($this->data[$offset]) << 32
                  );
            PHP,
        pack: <<<'PHP'
            if ($this->endianness === Endianness::LITTLE) {
                $this->data[$offset] = \chr($value);
                $this->data[$offset + 1] = \chr($value >> 8);
                $this->data[$offset + 2] = \chr($value >> 16);
                $this->data[$offset + 3] = \chr($value >> 24);
                $this->data[$offset + 4] = \chr($value >> 32);
            } else {
                $this->data[$offset] = \chr($value >> 32);
                $this->data[$offset + 1] = \chr($value >> 24);
                $this->data[$offset + 2] = \chr($value >> 16);
                $this->data[$offset + 3] = \chr($value >> 8);
                $this->data[$offset + 4] = \chr($value);
            }
            PHP,
        endianness: true,
        precondition: <<<'PHP'
            if (\PHP_INT_SIZE < self::ELEMENT_BYTES) {
                throw new \LogicException('The current platform does not support int40 arrays');
            }
            PHP,
    ),
    // i48
    new Sample(
        class: 'Int48Array',
        type: 'int',
        default: '0',
        from: '-140737488355327-1',
        to: 140_737_488_355_327,
        bytesPerElement: 6,
        unpack: <<<'PHP'
            ($lo = \ord($this->data[$offset + 5])) & 0x80
                ? (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  | \ord($this->data[$offset + 3]) << 24
                  | \ord($this->data[$offset + 4]) << 32
                  | $lo << 40
                  ) - 0x0001_0000_0000_0000
                : (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  | \ord($this->data[$offset + 3]) << 24
                  | \ord($this->data[$offset + 4]) << 32
                  | $lo << 40
                  );
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            $this->data[$offset + 1] = \chr($value >> 8);
            $this->data[$offset + 2] = \chr($value >> 16);
            $this->data[$offset + 3] = \chr($value >> 24);
            $this->data[$offset + 4] = \chr($value >> 32);
            $this->data[$offset + 5] = \chr($value >> 40);
            PHP,
        precondition: <<<'PHP'
            if (\PHP_INT_SIZE < self::ELEMENT_BYTES) {
                throw new \LogicException('The current platform does not support int48 arrays');
            }
            PHP,
        fromDocBlock: '-140737488355328'
    ),
    new Sample(
        class: 'UInt48Array',
        type: 'int',
        default: '0',
        from: 0,
        to: 281_474_976_710_655,
        bytesPerElement: 6,
        unpack: <<<'PHP'
            $this->endianness === Endianness::LITTLE
                ? (\ord($this->data[$offset])
                  | \ord($this->data[$offset + 1]) << 8
                  | \ord($this->data[$offset + 2]) << 16
                  | \ord($this->data[$offset + 3]) << 24
                  | \ord($this->data[$offset + 4]) << 32
                  | \ord($this->data[$offset + 5]) << 40
                  )
                : (\ord($this->data[$offset + 5])
                  | \ord($this->data[$offset + 4]) << 8
                  | \ord($this->data[$offset + 3]) << 16
                  | \ord($this->data[$offset + 2]) << 24
                  | \ord($this->data[$offset + 1]) << 32
                  | \ord($this->data[$offset]) << 40
                  );
            PHP,
        pack: <<<'PHP'
            if ($this->endianness === Endianness::LITTLE) {
                $this->data[$offset] = \chr($value);
                $this->data[$offset + 1] = \chr($value >> 8);
                $this->data[$offset + 2] = \chr($value >> 16);
                $this->data[$offset + 3] = \chr($value >> 24);
                $this->data[$offset + 4] = \chr($value >> 32);
                $this->data[$offset + 5] = \chr($value >> 40);
            } else {
                $this->data[$offset] = \chr($value >> 40);
                $this->data[$offset + 1] = \chr($value >> 32);
                $this->data[$offset + 2] = \chr($value >> 24);
                $this->data[$offset + 3] = \chr($value >> 16);
                $this->data[$offset + 4] = \chr($value >> 8);
                $this->data[$offset + 5] = \chr($value);
            }
            PHP,
        endianness: true,
        precondition: <<<'PHP'
            if (\PHP_INT_SIZE < self::ELEMENT_BYTES) {
                throw new \LogicException('The current platform does not support int48 arrays');
            }
            PHP,
    ),
    // i64
    new Sample(
        class: 'Int64Array',
        type: 'int',
        default: '0',
        // Avoid int64 overflow on x64 platforms
        from: '-9223372036854775807-1',
        to: '9223372036854775807',
        bytesPerElement: 8,
        unpack: <<<'PHP'
            (int)(\unpack('q', \substr($this->data, $offset, 8))[1]);
            PHP,
        pack: <<<'PHP'
            $this->data[$offset] = \chr($value);
            $this->data[$offset + 1] = \chr($value >> 8);
            $this->data[$offset + 2] = \chr($value >> 16);
            $this->data[$offset + 3] = \chr($value >> 24);
            $this->data[$offset + 4] = \chr($value >> 32);
            $this->data[$offset + 5] = \chr($value >> 40);
            $this->data[$offset + 6] = \chr($value >> 48);
            $this->data[$offset + 7] = \chr($value >> 56);
            PHP,
        precondition: <<<'PHP'
            if (\PHP_INT_SIZE < self::ELEMENT_BYTES) {
                throw new \LogicException('The current platform does not support int64 arrays');
            }
            PHP,
        fromDocBlock: (string)\PHP_INT_MIN,
    ),
    // f32
    new Sample(
        class: 'Float32Array',
        type: 'float',
        default: '0.0',
        from: '-340282346638528859811704183484516925440.',
        to: '340282346638528859811704183484516925440.',
        bytesPerElement: 4,
        unpack: <<<'PHP'
            (float)(\unpack($this->endianess === Endianness::LITTLE ? 'g' : 'G', \substr($this->data, $offset, 4))[1]);
            PHP,
        pack: <<<'PHP'
            $bytes = \pack($this->endianess === Endianness::LITTLE ? 'g' : 'G', $value);
            $this->data[$offset] = $bytes[0];
            $this->data[$offset + 1] = $bytes[1];
            $this->data[$offset + 2] = $bytes[2];
            $this->data[$offset + 3] = $bytes[3];
            PHP,
        endianness: true,
        fromDocBlock: '1.175494351E-38',
        toDocBlock: '3.402823466E+38',
        implements: 'FloatArrayInterface'
    ),
    // f64
    new Sample(
        class: 'Float64Array',
        type: 'float',
        default: '0.0',
        from: '2.2250738585072014E-308',
        to: '1.7976931348623158E+308',
        bytesPerElement: 8,
        unpack: <<<'PHP'
            (float)(\unpack($this->endianess === Endianness::LITTLE ? 'e' : 'E', \substr($this->data, $offset, 8))[1]);
            PHP,
        pack: <<<'PHP'
            $bytes = \pack($this->endianess === Endianness::LITTLE ? 'e' : 'E', $value);
            $this->data[$offset] = $bytes[0];
            $this->data[$offset + 1] = $bytes[1];
            $this->data[$offset + 2] = $bytes[2];
            $this->data[$offset + 3] = $bytes[3];
            $this->data[$offset + 4] = $bytes[4];
            $this->data[$offset + 5] = $bytes[5];
            $this->data[$offset + 6] = $bytes[6];
            $this->data[$offset + 7] = $bytes[7];
            PHP,
        endianness: true,
        implements: 'FloatArrayInterface'
    ),
];

$expression = "\n            ";
$statement  = "\n        ";

foreach ($samples as $sample) {
    // Format PHP code
    {
        /** @psalm-suppress all */
        $sample->unpack = \implode($expression, \explode("\n", $sample->unpack));
        /** @psalm-suppress all */
        $sample->pack = \implode($statement, \explode("\n", $sample->pack));

        /** @psalm-suppress all */
        if ($sample->precondition) {
            $sample->precondition = \implode($statement, \explode("\n", $sample->precondition));
        }
    }

    $result = $twig->render('template.php.twig', [
        'sample' => $sample
    ]);

    \file_put_contents(__DIR__ . '/../src/' . $sample->class . '.php', $result);
}
