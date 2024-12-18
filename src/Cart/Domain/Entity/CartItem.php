<?php

namespace App\Cart\Domain\Entity;

use App\Shared\Domain\Entity\Entity;
use App\Shared\Domain\VO\Money;
use App\Shared\Domain\VO\Qty;
use App\Shared\Domain\VO\Sku;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "cart_item")]
class CartItem extends Entity
{

    #[ORM\Id]
    #[ORM\Column(name: "cart_item_id", type: "cart_item_id", unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private CartItemId $id;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: "cartItems")]
    #[ORM\JoinColumn(name: "cart_item_cart_id", referencedColumnName: "cart_id", nullable: false)]
    private Cart $cart;

    #[ORM\Column(name: "cart_item_sku", type: "sku")]
    private Sku $sku;

    #[ORM\Column(name: "cart_item_price", type: "money")]
    private Money $price;

    #[ORM\Column(name: "cart_item_quantity", type: "qty")]
    private Qty $quantity;

    public function __construct(
        CartItemId $id,
        Sku $sku,
        Money $price,
        Qty $quantity
    ) {
        $this->id = $id;
        $this->sku = $sku;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public static function create(
        $type,
        $color,
        $size,
        $price,
        $currency,
        $quantity,
    ): CartItem {
        return new self(
            new CartItemId(CartItemId::random()),
            new Sku($type, $color, $size),
            new Money($price, $currency),
            new Qty($quantity)
        );
    }

    public function getQuantity(): Qty
    {
        return $this->quantity;
    }

    public function getSku(): Sku
    {
        return $this->sku;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function isSameProduct(CartItem $item): bool
    {
        return $this->sku->equals($item->sku);
    }

    public function increaseQuantity(int $increment): void
    {
        $this->quantity = $this->quantity->add($increment);
    }
}
