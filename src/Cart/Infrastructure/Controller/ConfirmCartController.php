<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\ConfirmCart\ConfirmCartCommand;
use App\Cart\Application\ConfirmCart\ConfirmCartCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ConfirmCartController extends AbstractController
{
    public function __construct(
        private readonly ConfirmCartCommandHandler $handler
    ) {}

    #[Route(path: '/cart/{customerId}/confirm', name: 'cart_confirm', methods: "POST")]
    public function __invoke($customerId): JsonResponse
    {
        try {
            $this->handler->__invoke(new ConfirmCartCommand(
                $customerId
            ));
            
            return new JsonResponse([
                "status" => true,
                "message" => "Cart confirmed"
            ], JsonResponse::HTTP_OK);

        } catch(\Throwable $th) {
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage()
            ], $th->getCode());
        }
    }

}