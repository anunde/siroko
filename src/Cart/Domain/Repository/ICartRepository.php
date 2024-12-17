<?php 

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;

interface ICartRepository {
    public function save(Cart $cart): void;

    public function findById(CartId $id): ?Cart;

    public function remove(Cart $cart): void;
}