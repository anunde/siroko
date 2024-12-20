<?php

namespace App\Cart\Application\GetTotalItems;

final class GetTotalItemsQuery
{
    public function __construct(
        private readonly string $customerId,
    ) {}

    public function getCustomerId(): string
    {
        return $this->customerId;
    }
}
