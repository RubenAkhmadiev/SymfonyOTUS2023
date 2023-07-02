<?php

namespace App\Controller\Telegram;

use App\Controller\Telegram\Dto\OrderPaymentDto;
use App\Manager\Telegram\OrderManager;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TelegramController extends AbstractController
{
    public function __construct(
        private readonly OrderManager $orderManager
    )
    {
    }

    #[Route(path: '/telegram/web-app', name: 'telegram_web-app', methods: ['GET'])]
    public function webApp(): Response
    {

    }

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

    #[Route(path: '/telegram/pay', name: 'telegram_pay', methods: ['POST'])]
    public function orderPayment(Request $request): Response
    {
        $orderDto = OrderPaymentDto::fromRequest($request);
        $id = $this->orderManager->createOrder($orderDto);

        return new JsonResponse(['id' => $id], Response::HTTP_CREATED);
    }
}
