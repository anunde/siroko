<?php

namespace App\Tests\Unit\Cart\Application;

use App\Cart\Application\ConfirmCart\ConfirmCartCommand;
use App\Cart\Application\ConfirmCart\ConfirmCartCommandHandler;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Domain\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class ConfirmCartTest extends TestCase
{
    protected ICartRepository $repository;
    private ConfirmCartCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ICartRepository::class);
        $this->handler = new ConfirmCartCommandHandler($this->repository);
    }

    public function testConfirmCart(): void
    {
        $customerId = new CustomerId('550e8400-e29b-41d4-a716-446655440000');

        $cart = $this->createMock(Cart::class);

        $cart->expects($this->once())
            ->method('confirmPayment');

        $this->repository
            ->expects($this->once())
            ->method('findByCustomerId')
            ->with($customerId)
            ->willReturn($cart);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Cart $cart) {
                return true;
            }));

        $command = new ConfirmCartCommand($customerId->value());

        $this->handler->__invoke($command);
    }


    public function testConfirmCartFromNonexistentCart(): void
    {
        $this->repository->expects($this->once())
            ->method('findByCustomerId')
            ->willReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("You do not have any cart created!");

        $Command = new ConfirmCartCommand('550e8400-e29b-41d4-a716-446655440000');
        $this->handler->__invoke($Command);
    }
}
