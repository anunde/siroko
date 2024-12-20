<?php

namespace App\Cart\Application\GetTotalItems;

use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Domain\Exception\NotFoundException;

final readonly class GetTotalItemsQueryHandler
{
    public function __construct(
        private readonly ICartRepository $repository
    ) {}

    public function __invoke(GetTotalItemsQuery $query): int
    {
        if (null === $cart = $this->repository->findByCustomerId(new CustomerId($query->getCustomerId()))) {
            throw new NotFoundException("You do not have any cart created!");
        }

        return $cart->getTotalItems();
    }
}
