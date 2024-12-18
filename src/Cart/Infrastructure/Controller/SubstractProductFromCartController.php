<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\SubstractProductFromCart\SubstractProductFromCartCommand;
use App\Cart\Application\SubstractProductFromCart\SubstractProductFromCartCommandHandler;
use App\Shared\Infrastructure\Service\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SubstractProductFromCartController extends AbstractController
{
    public function __construct(
        private readonly SubstractProductFromCartCommandHandler $handler
    ) {}

    #[Route(path: '/cart/substract', name: 'cart_substract', methods: ["DELETE"])]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->handler->__invoke(new SubstractProductFromCartCommand(
                RequestService::getField($request, 'customerId'),
                RequestService::getField($request, 'sku'),
                RequestService::getField($request, 'quantity')
            ));
            
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

        } catch(\Throwable $th) {
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage()
            ], $th->getCode());
        }
    }

}