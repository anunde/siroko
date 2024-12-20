<?php

namespace App\Cart\Application\ConfirmCart;

final class ConfirmCartCommand
{
    public function __construct(
        private readonly string $customerId,
    ) {}

    public function getCustomerId(): string
    {
        return $this->customerId;
    }
}
