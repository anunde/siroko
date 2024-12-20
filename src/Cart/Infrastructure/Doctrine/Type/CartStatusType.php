<?php

namespace App\Cart\Infrastructure\Doctrine\Type;

use App\Cart\Domain\ValueObject\CartStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class CartStatusType extends StringType
{
    public const CART_STATUS = 'cart_status';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (!$value instanceof CartStatus) {
            throw new \InvalidArgumentException("Expected CartStatus object.");
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if ($value === null) {
            return null;
        }

        return new CartStatus($value);
    }

    public function getName(): string
    {
        return self::CART_STATUS;
    }
}

