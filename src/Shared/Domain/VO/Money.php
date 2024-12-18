<?php

namespace App\Shared\Domain\VO;

final class Money
{
    private float $amount;
    private string $currency;

    public function __construct(float $amount, string $currency = 'EUR')
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Amount cannot be negative.", 400);
        }

        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function value(): string
    {
        return sprintf('%s%.2f', $this->currency, $this->amount);
    }

    public function add(Money $other): Money
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException("Currencies do not match.");
        }

        return new Money($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): Money
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException("Currencies do not match.");
        }

        $newAmount = $this->amount - $other->amount;

        if ($newAmount < 0) {
            throw new \InvalidArgumentException("Resulting amount cannot be negative.");
        }

        return new Money($newAmount, $this->currency);
    }

    public function multiply(int $factor): Money
    {
        return new Money($this->amount * $factor, $this->currency);
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    public function __toString(): string
    {
        return sprintf('%.2f %s', $this->amount, $this->currency);
    }
}
