<?php

namespace App\Controller\Telegram;

//use http\Client\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TelegramController extends AbstractController
{
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

        return new JsonResponse(['data' => $request->request->get('id')], Response::HTTP_OK);

//        return new JsonResponse(['data' => $request->getQuery()], Response::HTTP_OK);
    }
}
