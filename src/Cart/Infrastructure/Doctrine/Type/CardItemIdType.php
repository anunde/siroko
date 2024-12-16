<?php

namespace App\Cart\Infrastructure\Doctrine\Type;

use App\Cart\Domain\Entity\CartItemId;
use App\Shared\Infrastructure\Doctrine\Type\UuidType;

class CartItemIdType extends UuidType
{
    const NAME = 'cart_item_id';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return CartItemId::class;
    }
}