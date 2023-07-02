<?php

namespace App\Controller\Telegram;

use App\Controller\Telegram\Dto\OrderPaymentDto;
use App\Manager\Telegram\ItemManager;
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
        private readonly OrderManager $orderManager,
        private readonly ItemManager  $itemManager
    )
    {
    }

    #[Route(path: '/telegram/web-app', name: 'telegram_web-app', methods: ['GET'])]
    public function webApp(): Response
    {
    }

    #[Route(path: '/telegram/products', name: 'telegram_products', methods: ['GET'])]
    public function getProducts(Request $request): Response
    {
        $page = (int) $request->query->get('page', 0);
        $perPage = (int) $request->query->get('perPage', 10);

        $items = $this->itemManager->getItems($page, $perPage);
        return new JsonResponse(['data' => $items], Response::HTTP_OK);
    }

    #[Route(path: '/telegram/pay', name: 'telegram_pay', methods: ['POST'])]
    public function orderPayment(Request $request): Response
    {
        $orderDto = OrderPaymentDto::fromRequest($request);
        $id = $this->orderManager->createOrder($orderDto);

        return new JsonResponse(['id' => $id], Response::HTTP_CREATED);
    }

    #[Route(path: '/telegram/user/orders/{id}', name: 'telegram_user_orders', methods: ['GET'])]
    public function getUserOrders(int $id): Response
    {
        $orders = $this->orderManager->userOrders($id);

        return new JsonResponse(['data' => $orders], Response::HTTP_OK);
    }
}
