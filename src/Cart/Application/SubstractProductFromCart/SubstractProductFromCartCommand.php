<?php

namespace App\Cart\Application\SubstractProductFromCart;

final class SubstractProductFromCartCommand
{
    public function __construct(
        private readonly string $customerId,
        private readonly string $sku,
        private readonly int $quantity
    ) {}

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
