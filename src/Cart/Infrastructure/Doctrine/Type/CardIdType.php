<?php

namespace App\Cart\Infrastructure\Doctrine\Type;

use App\Cart\Domain\Entity\CartId;
use App\Shared\Infrastructure\Doctrine\Type\UuidType;

class CartIdType extends UuidType
{
    const NAME = 'cart_id';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return CartId::class;
    }
}