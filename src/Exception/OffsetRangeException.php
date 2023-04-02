<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

class OffsetRangeException extends \OutOfRangeException implements OffsetExceptionInterface
{
    final public const CODE_UNDERFLOW = 0x01;
    final public const CODE_OVERFLOW = 0x02;

    protected const CODE_LAST = self::CODE_OVERFLOW;

    final protected function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        [$this->file, $this->line] = Backtrace::get();
    }

    /**
     * @param non-empty-string $class
     */
    public static function fromUnderflow(string $class, int $actual): static
    {
        $message = 'Offset of %s cannot be less than 0, but %s given';
        $message = \sprintf($message, $class, Printer::value($actual));

        return new static($message, self::CODE_UNDERFLOW);
    }

    /**
     * @param non-empty-string $class
     * @param int<0, max> $length
     */
    public static function fromOverflow(string $class, int $actual, int $length): static
    {
        $message = 'Offset of %s cannot be greater than %d, but %s given';
        $message = \sprintf($message, $class, $length, Printer::value($actual));

        return new static($message, self::CODE_OVERFLOW);
    }
}
