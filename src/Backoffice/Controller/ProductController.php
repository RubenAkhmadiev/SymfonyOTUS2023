<?php

namespace App\Backoffice\Controller;

use App\Backoffice\Entity\Product;
use App\Backoffice\RequestDto\Product\IndexRequestDto;
use App\Backoffice\Service\ProductService;
use App\Backoffice\View\Table;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    use ValidateTrait;

    public function __construct(
        EntityManagerInterface   $em,
        ValidatorInterface       $validator,
        protected ProductService $productService,
    ) {
        $this->setValidator($validator);
    }

    #[Route(
        path: '/admin/products',
        name: 'app_backoffice_products_index',
        methods: ['GET']
    )]
    public function index(Request $request): Response
    {
        /** @var IndexRequestDto $dto */
        $dto = $this->validate($request, IndexRequestDto::class);

        $result = $this->productService->getAll(
            limit: $dto->limit,
            page: $dto->page,
        );

        $body = array_map(static fn(Product $product) => [
            'id'    => $product->getId(),
            'title' => $product->getTitle(),
        ], $result['items']);

        return $this->render('backoffice/pages/products/index.html.twig', [
            'products' => (new Table())
                ->setHeader('ID')
                ->setHeader('Название')
                ->setData($body)
                ->setPage($dto->page, $result['has_more'], $dto->limit)
        ]);
    }
}
