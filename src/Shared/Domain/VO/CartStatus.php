<?php

namespace App\Cart\Domain\ValueObject;

use InvalidArgumentException;

final class CartStatus
{
    private const VALID_STATUSES = ['open', 'process_payment', 'close'];
    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::VALID_STATUSES, true)) {
            throw new InvalidArgumentException("Invalid cart status: {$value}. Allowed values are: " . implode(', ', self::VALID_STATUSES));
        }
        $this->value = $value;
    }

    public static function open(): self
    {
        return new self('open');
    }

    public static function processPayment(): self
    {
        return new self('process_payment');
    }

    public static function close(): self
    {
        return new self('close');
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isEqualTo(self $status): bool
    {
        return $this->value === $status->value();
    }
}
