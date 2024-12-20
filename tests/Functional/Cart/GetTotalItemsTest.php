<?php

namespace App\Tests\Functional\Cart;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetTotalItemsTest extends CartTestBase
{
    public function testGetTotalItemsWithExistingCart(): void
    {
        $customerId = "550e8400-e29b-41d4-a716-446655440000";

        self::$client->request(
            'GET',
            \sprintf('%s/total/%s', $this->endpoint, $customerId),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json'
            ]
        );

        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(3, $data['total']);
    }

    public function testGetTotalItemsWithNoCart(): void
    {
        $customerId = "550e8400-e29b-41d4-a716-446655440001";

        self::$client->request(
            'GET',
            \sprintf('%s/total/%s', $this->endpoint, $customerId),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json'
            ]
        );


        $response = self::$client->getResponse();

        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertStringContainsString('You do not have any cart created!', $response->getContent());
    }
}
