<?php

declare(strict_types=1);

/**
 * @internal This is an internal class
 */
class Sample
{
    /**
     * @param non-empty-string $class
     * @param non-empty-string $type
     * @param int<1, max> $bytesPerElement
     * @param non-empty-string $unpack
     */
    public function __construct(
        public string $class,
        public string $type,
        public string $default,
        public int|float|string $from,
        public int|float|string $to,
        public int $bytesPerElement,
        public string $unpack,
        public string $pack,
        public bool $endianness = false,
        public ?string $precondition = null,
        public ?string $fromDocBlock = null,
        public ?string $toDocBlock = null,
        public string $implements = 'IntArrayInterface'
    ) {
        if ($this->fromDocBlock === null) {
            $this->fromDocBlock = (string)$this->from;
        }
        if ($this->toDocBlock === null) {
            $this->toDocBlock = (string)$this->to;
        }
    }
}
