<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\GetTotalItems\GetTotalItemsQuery;
use App\Cart\Application\GetTotalItems\GetTotalItemsQueryHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class GetTotalItemsController extends AbstractController
{
    public function __construct(
        private readonly GetTotalItemsQueryHandler $handler
    ) {}

    #[Route(path: '/cart/total/{customerId}', name: 'cart_total')]
    public function __invoke($customerId): JsonResponse
    {
        try {
            $total = $this->handler->__invoke(new GetTotalItemsQuery(
                $customerId
            ));
            
            return new JsonResponse([
                "total" => (int) $total
            ], JsonResponse::HTTP_OK);

        } catch(\Throwable $th) {
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage()
            ], $th->getCode());
        }
    }

}