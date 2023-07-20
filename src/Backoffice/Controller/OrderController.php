<?php

namespace App\Backoffice\Controller;

use App\Adapter\CustomerAdapter;
use App\Adapter\Dto\OrderDto;
use App\Backoffice\RequestDto\Order\IndexRequestDto;
use App\Backoffice\View\Table;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderController extends AbstractController
{
    use ValidateTrait;

    public function __construct(
        protected CustomerAdapter $customerAdapter,
        ValidatorInterface        $validator,
    ) {
        $this->setValidator($validator);
    }

    #[Route(
        path: '/admin/orders',
        name: 'app_backoffice_orders_index',
        methods: ['GET']
    )]
    public function index(Request $request): Response
    {
        /** @var IndexRequestDto $dto */
        $dto = $this->validate($request, IndexRequestDto::class);

        $result = $this->customerAdapter->getOrders(
            page: $dto->page,
            limit: $dto->limit,
        );

        $body = array_map(static fn(OrderDto $orderDto) => [
            'id'            => $orderDto->id,
            'number'        => $orderDto->number,
            'status'        => $orderDto->status,
            'sum'           => $orderDto->sum,
            'creation_date' => $orderDto->creationDate,
        ], $result['items']);

        return $this->render('backoffice/pages/orders/index.html.twig', [
            'orders' => (new Table())
                ->setHeader('ID')
                ->setHeader('Кол-во')
                ->setHeader('Статус')
                ->setHeader('Сумма')
                ->setHeader('Дата создания')
                ->setData($body)
                ->setPage($dto->page, $result['has_more'], $dto->limit)
        ]);
    }
}
