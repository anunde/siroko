<?php

namespace App\Tests\Unit\Cart\Application;

use App\Cart\Application\GetTotalItems\GetTotalItemsQuery;
use App\Cart\Application\GetTotalItems\GetTotalItemsQueryHandler;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartItem;
use App\Cart\Domain\Repository\ICartRepository;
use App\Shared\Domain\Entity\CustomerId;
use App\Shared\Domain\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class GetTotalItemsTest extends TestCase
{
    protected ICartRepository $repository;
    private GetTotalItemsQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ICartRepository::class);
        $this->handler = new GetTotalItemsQueryHandler($this->repository);
    }

    public function testGetTotalItemsReturnsCorrectCount(): void
    {
        $customerId = new CustomerId('550e8400-e29b-41d4-a716-446655440000');
        $cart = $this->createMock(Cart::class);

        $cart->expects($this->once())
            ->method('getTotalItems')
            ->willReturn(3);

        $this->repository
            ->expects($this->once())
            ->method('findByCustomerId')
            ->with($customerId)
            ->willReturn($cart);

        $query = new GetTotalItemsQuery($customerId->value());

        $result = $this->handler->__invoke($query);
        $this->assertEquals(3, $result);
    }

    public function testGetTotalItemsFromNonexistentCart(): void
    {
        $this->repository->expects($this->once())
            ->method('findByCustomerId')
            ->willReturn(null);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage("You do not have any cart created!");

        $query = new GetTotalItemsQuery('550e8400-e29b-41d4-a716-446655440000');
        $this->handler->__invoke($query);
    }
}
