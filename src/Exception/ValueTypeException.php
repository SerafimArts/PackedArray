<?php

declare(strict_types=1);

namespace Serafim\PackedArray\Exception;

class ValueTypeException extends \TypeError implements ValueExceptionInterface
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
     * @param non-empty-string $expected
     */
    public static function fromInvalidType(string $class, mixed $actual): static
    {
        $message = 'Can not assign value of type %s to the %s';
        $message = \sprintf($message, Printer::value($actual), $class);

        return new static($message, self::CODE_INVALID_TYPE);
    }
}
