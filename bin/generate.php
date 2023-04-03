<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/generate/Sample.php';

$twig = new Environment(new FilesystemLoader(__DIR__ . '/generate'));

$samples = [
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
    new Sample(
        class: 'Int24Array',
        type: 'int',
        default: '0',
        from: -8388608,
        to: 8388607,
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
        to: 16777215,
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
    new Sample(
        class: 'Int32Array',
        type: 'int',
        default: '0',
        // Avoid int32 overflow on x86 platforms
        from: '-2147483647-1',
        to: 2147483647,
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
        to: 4294967295,
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
