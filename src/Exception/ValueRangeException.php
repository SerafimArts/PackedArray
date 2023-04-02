<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

class ValueRangeException extends \OutOfRangeException implements ValueExceptionInterface
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
    public static function fromUnderflow(string $class, mixed $actual): static
    {
        $message = 'Can not assign value of type %s to the %s';
        $message = \sprintf($message, Printer::value($actual), $class);

        return new static($message, self::CODE_UNDERFLOW);
    }

    /**
     * @param non-empty-string $class
     */
    public static function fromOverflow(string $class, mixed $actual): static
    {
        $message = 'Can not assign value of type %s to the %s';
        $message = \sprintf($message, Printer::value($actual), $class);

        return new static($message, self::CODE_OVERFLOW);
    }
}
