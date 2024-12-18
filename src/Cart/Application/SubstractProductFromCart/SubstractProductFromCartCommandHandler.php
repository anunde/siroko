<?php

namespace App\Cart\Application\SubstractProductFromCart;

use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Domain\Exception\NotFoundException;

final readonly class SubstractProductFromCartCommandHandler
{
    public function __construct(
        private readonly ICartRepository $repository
    ) {}

    public function __invoke(SubstractProductFromCartCommand $command): void
    {
        $cart = $this->repository->findByCustomerId(new CustomerId($command->getCustomerId()));

        if (null === $cart) {
            throw new NotFoundException("You don't have any cart created!");
        }

        $item = $cart->findItem($command->getSku());

        if (null === $item) {
            throw new NotFoundException("Product not found in the cart!");
        }

        $item->decreaseQuantity($command->getQuantity());

        if ($item->getQuantity()->value() === 0) {
            $cart->removeItem($item);
        }

        if ($cart->isEmpty()) {
            $this->repository->remove($cart);
        } else {
            $this->repository->save($cart);
        }
    }
}
