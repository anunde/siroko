<?php

namespace App\Cart\Application\AddProductToCart;

final class AddProductToCartCommand
{
    public function __construct(
        private readonly string $customerId,
        private readonly string $type,
        private readonly string $color,
        private readonly int $size,
        private readonly float $price,
        private readonly string $currency,
        private readonly int $quantity
    ) {}

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
