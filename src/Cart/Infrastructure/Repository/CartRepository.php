<?php

namespace App\Cart\Infrastructure\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Infrastructure\DataSource\DoctrineDataSource;

class CartRepository implements ICartRepository
{
    private DoctrineDataSource $dataSource;

    public function __construct(DoctrineDataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    public function save(Cart $cart): void
    {
        $this->dataSource->persist($cart, true);
    }

    public function findByCustomerId(CustomerId $customerId): ?Cart
    {
        $queryBuilder = $this->dataSource->entityManager()
            ->getRepository(Cart::class)
            ->createQueryBuilder('c');

        $queryBuilder->where('c.customerId = :customerId')
            ->andWhere('c.status != :closedStatus')
            ->setParameter('customerId', $customerId)
            ->setParameter('closedStatus', 'close');

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function remove(Cart $cart): void
    {
        $this->dataSource->remove($cart, true);
    }
}
