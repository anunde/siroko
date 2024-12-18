<?php

namespace App\Shared\Domain\VO;

final class Qty
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("Quantity must be greater than zero.", 400);
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function add(int $increment): Qty
    {
        return new self($this->value + $increment);
    }

    public function subtract(int $decrement): Qty
    {
        $newValue = $this->value - $decrement;

        if ($newValue < 0) {
            throw new \InvalidArgumentException("Resulting quantity must be greater than zero.", 400);
        }

        return new self($newValue);
    }

    public function equals(Qty $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
