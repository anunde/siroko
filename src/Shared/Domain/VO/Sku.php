<?php

namespace App\Shared\Domain\VO;

final class Sku
{
    private string $type;
    private string $color;
    private string $size;

    private string $value;

    public function __construct(string $type, string $color, string $size)
    {
        $this->ensureIsValid($type, $color, $size);

        $this->type = strtoupper($type);
        $this->color = strtoupper($color);
        $this->size = strtoupper($size);

        $this->value = $this->generateSku($this->type, $this->color, $this->size);
    }

    private function ensureIsValid(string $type, string $color, string $size): void
    {
        if (!preg_match('/^[A-Z]+$/i', $type)) {
            throw new \InvalidArgumentException("Invalid type: $type. Only alphabetic characters are allowed.");
        }

        if (!preg_match('/^[A-Z]+$/i', $color)) {
            throw new \InvalidArgumentException("Invalid color: $color. Only alphabetic characters are allowed.");
        }

        if (!preg_match('/^[A-Z0-9]+$/i', $size)) {
            throw new \InvalidArgumentException("Invalid size: $size. Only alphanumeric characters are allowed.");
        }
    }

    private function generateSku(string $type, string $color, string $size): string
    {
        return sprintf('%s-%s-%s', $type, $color, $size);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Sku $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
