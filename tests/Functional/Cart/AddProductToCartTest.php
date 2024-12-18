<?php

namespace App\Tests\Functional\Cart;

use Symfony\Component\HttpFoundation\JsonResponse;

class AddProductToCartTest extends CartTestBase
{
    public function testAddProductToCart(): void
    {
        $payload = [
            "customerId" => "550e8400-e29b-41d4-a716-446655440000",
            "type" => "shirt",
            "color" => "blue",
            "size" => 42,
            "price" => 9.99,
            "currency" => "EUR",
            "quantity" => 3
        ];

        self::$client->request(
            'POST',
            \sprintf('%s/add', $this->endpoint),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json'
            ],
            json_encode($payload)
        );

        $response = self::$client->getResponse();
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertStringContainsString('Product added to cart!', $response->getContent());
    }

    public function testAddProductToCartMissingField(): void
    {
        $payload = [
            "customerId" => "550e8400-e29b-41d4-a716-446655440000",
            "type" => "shirt",
            // Falta "color"
            "size" => 42,
            "price" => 9.99,
            "currency" => "EUR",
            "quantity" => 3
        ];

        self::$client->request(
            'POST',
            \sprintf('%s/add', $this->endpoint),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json'
            ],
            json_encode($payload)
        );

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertStringContainsString('The field color is required!', $response->getContent());
    }

    public function testAddProductToCartInvalidPrice(): void
{
    $payload = [
        "customerId" => "550e8400-e29b-41d4-a716-446655440000",
        "type" => "shirt",
        "color" => "blue",
        "size" => 42,
        "price" => -9.99, // Precio inválido
        "currency" => "EUR",
        "quantity" => 3
    ];

    self::$client->request(
        'POST',
        \sprintf('%s/add', $this->endpoint),
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
            'ACCEPT' => 'application/json'
        ],
        json_encode($payload)
    );

    $response = self::$client->getResponse();

    $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    $this->assertStringContainsString('Amount cannot be negative.', $response->getContent());
}
}
