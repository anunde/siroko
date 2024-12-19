<?php

namespace App\Tests\Unit\Cart\Application;

use App\Cart\Application\SubstractProductFromCart\SubstractProductFromCartCommand;
use App\Cart\Application\SubstractProductFromCart\SubstractProductFromCartCommandHandler;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class SubstractProductFromCartTest extends TestCase
{
    protected ICartRepository $repository;
    private SubstractProductFromCartCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ICartRepository::class);
        $this->handler = new SubstractProductFromCartCommandHandler($this->repository);
    }

    public function testSubstractProductSuccessfully(): void
    {
        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $sku = 'SHIRT-RED-42';
        $quantity = 1;

        $cart = $this->createMock(Cart::class);
        $item = $this->createMock(CartItem::class);

        $cart->expects($this->once())->method('findItem')->with($sku)->willReturn($item);
        $item->expects($this->once())->method('decreaseQuantity')->with($quantity);
        $cart->expects($this->once())->method('isEmpty')->willReturn(false);

        $this->repository->expects($this->once())
            ->method('findByCustomerId')
            ->with($customerId)
            ->willReturn($cart);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($cart);

        $command = new SubstractProductFromCartCommand($customerId, $sku, $quantity);
        $this->handler->__invoke($command);
    }


    public function testSubstractProductFromNonexistentCart(): void
    {
        $this->repository->expects($this->once())
            ->method('findByCustomerId')
            ->willReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("You do not have any cart created!");

        $command = new SubstractProductFromCartCommand('550e8400-e29b-41d4-a716-446655440000', 'SKU123', 1);
        $this->handler->__invoke($command);
    }

    public function testSubstractProductNotFoundInCart(): void
    {
        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $sku = 'SHIRT-RED-42';
        $quantity = 1;
        $cart = $this->createMock(Cart::class);

        $cart->expects($this->once())->method('findItem')->with($sku)->willReturn(null);
        $this->repository->expects($this->once())->method('findByCustomerId')->willReturn($cart);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("Product not found in the cart!");

        $command = new SubstractProductFromCartCommand($customerId, $sku, $quantity);
        $this->handler->__invoke($command);
    }

    public function testSubstractProductAndRemoveItemWhenQuantityIsZero(): void
    {
        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $sku = 'SHOES-BLACK-38';
        $quantity = 2;

        $cart = $this->createMock(Cart::class);
        $item = $this->createMock(CartItem::class);

        $this->repository->expects($this->once())
            ->method('findByCustomerId')
            ->with($customerId)
            ->willReturn($cart);

        $cart->expects($this->once())->method('findItem')->with($sku)->willReturn($item);
        $item->expects($this->once())->method('decreaseQuantity')->with($quantity);
        $cart->expects($this->once())->method('removeItem')->with($item);
        $this->repository->expects($this->once())->method('save')->with($cart);

        $command = new SubstractProductFromCartCommand($customerId, $sku, $quantity);
        $this->handler->__invoke($command);
    }

    public function testRemoveCartWhenEmpty(): void
    {
        $customerId = '550e8400-e29b-41d4-a716-446655440000';
        $sku = 'SHOES-BLACK-38';
        $quantity = 2;

        $cart = $this->createMock(Cart::class);
        $item = $this->createMock(CartItem::class);

        $this->repository->expects($this->once())
        ->method('findByCustomerId')
        ->with($customerId)
        ->willReturn($cart);

        $cart->expects($this->once())->method('findItem')->with($sku)->willReturn($item);
        $item->expects($this->once())->method('decreaseQuantity')->with($quantity);
        $cart->expects($this->once())->method('removeItem')->with($item);
        $cart->expects($this->once())->method('isEmpty')->willReturn(true);
        $this->repository->expects($this->once())->method('remove')->with($cart);

        $command = new SubstractProductFromCartCommand($customerId, $sku, $quantity);
        $this->handler->__invoke($command);
    }
}
