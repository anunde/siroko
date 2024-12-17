<?php

namespace App\Cart\Infrastructure\Controller;

use App\Cart\Application\AddProductToCart\AddProductToCartCommand;
use App\Cart\Application\AddProductToCart\AddProductToCartCommandHandler;
use App\Shared\Infrastructure\Service\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AddProductToCartController extends AbstractController
{
    public function __construct(
        private readonly AddProductToCartCommandHandler $handler
    ) {}

    #[Route(path: '/cart/add', name: 'cart_add', methods: "POST")]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->handler->__invoke(new AddProductToCartCommand(
                RequestService::getField($request, 'customerId'),
                RequestService::getField($request, 'type'),
                RequestService::getField($request, 'color'),
                RequestService::getField($request, 'size'),
                RequestService::getField($request, 'price'),
                RequestService::getField($request, 'currency'),
                RequestService::getField($request, 'quantity')
            ));
            
            return new JsonResponse([
                "status" => true,
                "message" => "Product added to cart!"
            ], 201);

        } catch(\Throwable $th) {
            return new JsonResponse([
                "status" => false,
                "message" => $th->getMessage()
            ], 500);
        }
    }

}