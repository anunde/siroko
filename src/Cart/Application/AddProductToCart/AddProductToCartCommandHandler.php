<?php

namespace App\Cart\Application\AddProductToCart;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Exception\CartInProcessPaymentException;
use App\Cart\Domain\Repository\ICartRepository;
use App\Cart\Domain\ValueObject\CartStatus;
use App\Shared\Domain\Entity\CustomerId;

final readonly class AddProductToCartCommandHandler
{
    public function __construct(
        private readonly ICartRepository $repository
    ) {}

    public function __invoke(AddProductToCartCommand $command): void 
    {
        $cartItem = CartItem::create(
            $command->getType(),
            $command->getColor(),
            $command->getSize(),
            $command->getPrice(),
            $command->getCurrency(),
            $command->getQuantity()
        );

        if(null === $cart = $this->repository->findByCustomerId(new CustomerId($command->getCustomerId()))) {
            $cart = Cart::create($command->getCustomerId());
        }

        if ($cart->getCartStatus()->isEqualTo(CartStatus::processPayment())) {
            throw new CartInProcessPaymentException("Cart is in process of payment and cannot be modified");
        }

        $cart->addCartItem($cartItem);
        $this->repository->save($cart);
    }
}