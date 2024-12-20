<?php

namespace App\Cart\Application\ConfirmCart;

use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Domain\Exception\NotFoundException;

final readonly class ConfirmCartCommandHandler
{
    public function __construct(
        private readonly ICartRepository $repository
    ) {}

    public function __invoke(ConfirmCartCommand $command): void
    {
        if (null === $cart = $this->repository->findByCustomerId(new CustomerId($command->getCustomerId()))) {
            throw new NotFoundException("You do not have any cart created!");
        }

        $cart->confirmPayment();
        $this->repository->save($cart);
    }
}
