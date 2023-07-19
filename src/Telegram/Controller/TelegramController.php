<?php

namespace App\Telegram\Controller;

use App\Adapter\CustomerAdapter;
use App\Telegram\Controller\Dto\OrderPaymentDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TelegramController extends AbstractController
{
    public function __construct(
        private readonly CustomerAdapter $customerAdapter
    )
    {
    }

    #[Route(path: '/telegram/web-app', name: 'telegram_web-app', methods: ['GET'])]
    public function webApp(): Response
    {
        return $this->render('telegram/pages/index.html.twig');
    }

    #[Route(path: '/telegram/products', name: 'telegram_products', methods: ['GET'])]
    public function getProducts(Request $request): Response
    {
        $page = (int) $request->query->get('page', 0);
        $perPage = (int) $request->query->get('perPage', 6);

        $items = $this->customerAdapter->getProducts($page, $perPage);
        return new JsonResponse($items, Response::HTTP_OK);
    }


    #[Route(path: '/telegram/pay', name: 'telegram_pay', methods: ['POST'])]
    public function orderPayment(Request $request): Response
    {
        $requestDto = OrderPaymentDto::fromRequest($request);

        $userId = $this->customerAdapter->checkExistsUser($requestDto->telegramId);

        if ($userId) {
            $user = $this->customerAdapter->updateUser($userId, $requestDto);
        } else {
            $user = $this->customerAdapter->createUser($requestDto);
        }

        $orderId = $this->customerAdapter->createOrder($user, $requestDto);

        return new JsonResponse(['id' => $orderId], Response::HTTP_CREATED);
    }


    #[Route(path: '/telegram/user/orders/{id}', name: 'telegram_user_orders', methods: ['GET'])]
    public function getUserOrders(int $id): Response
    {
        $orders = $this->customerAdapter->userOrders($id);

        return new JsonResponse(['data' => $orders], Response::HTTP_OK);
    }
}
