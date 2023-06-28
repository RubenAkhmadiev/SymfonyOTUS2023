<?php

namespace App\Controller\Telegram;

use http\Client\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class TelegramController
{
    #[Route(path: '/telegram/products', name: 'telegram_products', methods: ['GET'])]
    public function getProducts(): Response
    {
        $data = [
            [
                'name' => 'Apple',
                'price' => '10',
                'summary' => 'Green apple'
            ],
            [
                'name' => 'Orange',
                'price' => '10',
                'summary' => 'Green apple'
            ],
            [
                'name' => 'Corn',
                'price' => '10',
                'summary' => 'Green apple'
            ],
        ];
        return new JsonResponse(['data' => $data], Response::HTTP_OK);
    }

    #[Route(path: '/telegram/pay', name: 'telegram_products', methods: ['POST'])]
    public function orderPayment(Request $request): Response
    {
        return new JsonResponse(['data' => $request->getQuery()], Response::HTTP_OK);
    }
}
