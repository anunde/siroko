<?php

namespace App\Tests\Unit\Cart\Application;

use App\Cart\Application\AddProductToCart\AddProductToCartCommand;
use App\Cart\Application\AddProductToCart\AddProductToCartCommandHandler;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use PHPUnit\Framework\TestCase;

class AddProductToCartTest extends TestCase
{
    protected ICartRepository $repository;
    private AddProductToCartCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ICartRepository::class);
        $this->handler = new AddProductToCartCommandHandler($this->repository);
    }

    public function testCreateNewCartIfNoneExists(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('findByCustomerId')
            ->with($this->equalTo(new CustomerId('550e8400-e29b-41d4-a716-446655440000')))
            ->willReturn(null);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Cart $cart) {
                $this->assertCount(1, $cart->getItems());
                $item = $cart->getItems()->first();
                $this->assertInstanceOf(CartItem::class, $item);
                $this->assertEquals('SHIRT', $item->getSku()->getType());
                return true;
            }));

        $command = new AddProductToCartCommand(
            '550e8400-e29b-41d4-a716-446655440000',
            'shirt',
            'blue',
            42,
            9.99,
            'EUR',
            3
        );

        $this->handler->__invoke($command);
    }
}
