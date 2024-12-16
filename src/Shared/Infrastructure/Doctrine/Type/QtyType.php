<?php

namespace App\Shared\Infrastructure\Doctrine\Type;

use App\Shared\Domain\VO\Qty;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

final class QtyType extends IntegerType
{
    public const QTY = 'qty';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof Qty) {
            throw new \InvalidArgumentException("Expected Qty object.");
        }

        return $value->value();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new Qty((int) $value);
    }

    public function getName(): string
    {
        return self::QTY;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
