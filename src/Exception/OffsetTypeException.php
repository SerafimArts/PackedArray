<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

class OffsetTypeException extends \TypeError implements OffsetExceptionInterface
{
    final public const CODE_INVALID_TYPE = 0x01;

    protected const CODE_LAST = self::CODE_INVALID_TYPE;

    final protected function __construct(string $message, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        [$this->file, $this->line] = Backtrace::get();
    }

    /**
     * @param non-empty-string $class
     */
    public static function fromInvalidType(string $class, mixed $actual): static
    {
        $message = 'Offset of %s must be of type int, but %s given';
        $message = \sprintf($message, $class, Printer::value($actual));

        return new static($message, self::CODE_INVALID_TYPE);
    }
}
