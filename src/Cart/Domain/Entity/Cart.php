<?php

namespace App\Cart\Domain\Entity;

use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Domain\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cart")]
class Cart extends Entity
{

    #[ORM\Id]
    #[ORM\Column(name: "cart_id", type: "cart_id", unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private CartId $id;

    #[ORM\Column(name: "cart_customer_id", type: "customer_id")]
    private CustomerId $customerId;

    #[ORM\Column(name: "cart_created_at", type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\OneToMany(mappedBy: "cart", targetEntity: CartItem::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $cartItems;

    public function __construct(
        CartId $id,
        CustomerId $customerId,
        \DateTime $createdAt
    ) {
        $this->id = $id;
        $this->customerId = $customerId;
        $this->createdAt = $createdAt;
        $this->cartItems = new ArrayCollection();
    }

    public static function create(
        $customerId
    ): Cart {
        return new self(
            new CartId(CartId::random()),
            new CustomerId($customerId),
            new \DateTime()
        );
    }

    public function getItems(): Collection
    {
        return $this->cartItems;
    }

    public function findItem(string $sku): ?CartItem
    {
        foreach ($this->cartItems as $item) {
            if ($item->getSku()->value() === $sku) {
                return $item;
            }
        }

        return null;
    }

    public function removeItem(CartItem $item): void
    {
        $this->cartItems->removeElement($item);
    }

    public function isEmpty(): bool
    {
        return $this->cartItems->isEmpty();
    }

    public function addCartItem(CartItem $item): void
    {
        foreach ($this->cartItems as $existingItem) {
            if ($existingItem->isSameProduct($item)) {
                $existingItem->increaseQuantity($item->getQuantity()->value());
                return;
            }
        }

        $this->cartItems->add($item);
        $item->setCart($this);
    }
}
