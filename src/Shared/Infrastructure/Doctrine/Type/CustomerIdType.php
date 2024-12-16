<?php

namespace App\Shared\Infrastructure\Doctrine\Type;

use App\Shared\Domain\Entity\CustomerId;

class CustomerIdType extends UuidType
{
    const NAME = 'customer_id';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return CustomerId::class;
    }
}