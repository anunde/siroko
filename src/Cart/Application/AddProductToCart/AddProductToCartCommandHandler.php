<?php

namespace App\Cart\Application\AddProductToCart;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Domain\VO\Sku;

final readonly class AddProductToCartCommandHandler
{
    public function __construct(
        private readonly ICartRepository $repository
    ) {}

    public function __invoke(AddProductToCartCommand $command): void 
    {
        if(null === $cart = $this->repository->findByCustomerId(new CustomerId($command->getCustomerId()))) {
            $cart = Cart::create($command->getCustomerId());
        }

        if(null === $item = $cart->findItem(new Sku($command->getType(), $command->getColor(), $command->getSize()))) {
            $item = CartItem::create(
                $command->getType(),
                $command->getColor(),
                $command->getSize(),
                $command->getPrice(),
                $command->getCurrency(),
                $command->getQuantity()
            );
        }

        $cart->addCartItem($item);
        $this->repository->save($cart);
    }
}