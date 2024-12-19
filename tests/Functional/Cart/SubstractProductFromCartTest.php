<?php

namespace App\Tests\Functional\Cart;

use App\Cart\Domain\Entity\Cart;
use App\Shared\Domain\Entity\CustomerId;
use Symfony\Component\HttpFoundation\JsonResponse;

class SubstractProductFromCartTest extends CartTestBase
{
    private const ENDPOINT = '/cart/substract';

    public function testSubstractProductSuccessfully(): void
    {
        $customerId = "550e8400-e29b-41d4-a716-446655440000";
        $sku = "SHIRT-RED-42";

        $payload = [
            "customerId" => $customerId,
            "sku" => $sku,
            "quantity" => 1
        ];

        $response = $this->sendDeleteRequest($payload);

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());

        $updatedCart = $this->getCartByCustomerId($customerId);
        $this->assertNotNull($updatedCart);
        $this->assertEquals(1, $updatedCart->findItem($sku)->getQuantity()->value());
    }

    public function testSubstractProductCompletelyRemovesItem(): void
    {
        $customerId = "550e8400-e29b-41d4-a716-446655440000";
        $sku = "SHOES-BLACK-38";

        $payload = [
            "customerId" => $customerId,
            "sku" => $sku,
            "quantity" => 1
        ];

        $response = $this->sendDeleteRequest($payload);

        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());

        $updatedCart = $this->getCartByCustomerId($customerId);
        $this->assertNotNull($updatedCart);
        $this->assertNull($updatedCart->findItem($sku));
    }

    public function testSubstractProductNotFoundInCart(): void
    {
        $customerId = "550e8400-e29b-41d4-a716-446655440000";
        $sku = "NONE-BLACK-0";

        $payload = [
            "customerId" => $customerId,
            "sku" => $sku,
            "quantity" => 1
        ];

        $response = $this->sendDeleteRequest($payload);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertStringContainsString("Product not found in the cart!", $response->getContent());
    }

    public function testSubstractFromNonexistentCart(): void
    {
        $customerId = "550e8400-e29b-41d1-a716-422555440000";

        $payload = [
            "customerId" => $customerId,
            "sku" => "NONE-BLACK-0",
            "quantity" => 1
        ];

        $response = $this->sendDeleteRequest($payload);

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertStringContainsString("You do not have any cart created!", $response->getContent());
    }

    private function sendDeleteRequest(array $payload): JsonResponse
    {
        self::$client->request(
            'DELETE',
            self::ENDPOINT,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json'
            ],
            json_encode($payload)
        );

        return self::$client->getResponse();
    }

    private function getCartByCustomerId(string $customerId): ?Cart
    {
        $repository = self::$kernel->getContainer()->get('doctrine')->getRepository(Cart::class);
        return $repository->findOneBy(['customerId' => new CustomerId($customerId)]);
    }
}
