<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

class ArraySizeException extends \OutOfRangeException implements ArrayExceptionInterface
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
    public static function fromUnderflow(string $class, int $size): static
    {
        $message = '%s size cannot be less than %d';

        return new static(\sprintf($message, $class, $size), self::CODE_UNDERFLOW);
    }

    /**
     * @param non-empty-string $class
     */
    public static function fromOverflow(string $class, int $size): static
    {
        $message = '%s size cannot be greater than %d (memory overflow)';

        return new static(\sprintf($message, $class, $size), self::CODE_OVERFLOW);
    }
}
