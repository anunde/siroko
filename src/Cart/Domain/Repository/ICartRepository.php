<?php 

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Shared\Domain\Entity\CustomerId;

interface ICartRepository {
    public function save(Cart $cart): void;

    public function findByCustomerId(CustomerId $customerId): ?Cart;

    public function remove(Cart $cart): void;
}