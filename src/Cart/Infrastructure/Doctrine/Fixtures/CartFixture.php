<?php

namespace App\Cart\Infrastructure\Doctrine\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;

class CartFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cart = Cart::create('550e8400-e29b-41d4-a716-446655440000');

        $item1 = CartItem::create(
            type: 'shirt',
            color: 'red',
            size: 42,
            price: 9.99,
            currency: 'EUR',
            quantity: 1
        );

        $item2 = CartItem::create(
            type: 'shoes',
            color: 'black',
            size: 38,
            price: 40.55,
            currency: 'EUR',
            quantity: 1
        );

        $cart->addCartItem($item1);
        $cart->addCartItem($item2);

        $manager->persist($cart);
        $manager->flush();
    }
}
