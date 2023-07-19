<?php

namespace App\Backoffice\Controller;

use App\Backoffice\Entity\Category;
use App\Backoffice\RequestDto\Category\IndexRequestDto;
use App\Backoffice\RequestDto\Category\StoreRequestDto;
use App\Backoffice\Service\CategoryService;
use App\Backoffice\View\Table;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    use ValidateTrait;

    public function __construct(
        EntityManagerInterface    $em,
        ValidatorInterface        $validator,
        protected CategoryService $categoryService,
    ) {
        $this->setValidator($validator);
    }

    #[Route(
        path: '/admin/categories',
        name: 'app_backoffice_categories_index',
        methods: ['GET']
    )]
    public function index(Request $request): Response
    {
        /** @var IndexRequestDto $dto */
        $dto = $this->validate($request, IndexRequestDto::class);

        $result = $this->categoryService->getAll(
            limit: $dto->limit,
            page: $dto->page,
        );

        $body = array_map(static fn(Category $category) => [
            'id'   => $category->getId(),
            'name' => $category->getName(),
        ], $result['items']);

        return $this->render('backoffice/pages/categories/index.html.twig', [
            'categories' => (new Table())
                ->setHeader('ID')
                ->setHeader('Название')
                ->setData($body)
                ->setPage($dto->page, $result['has_more'], $dto->limit)
        ]);
    }

    #[Route(
        path: '/admin/categories/create',
        name: 'app_backoffice_categories_create',
        methods: ['GET']
    )]
    public function create(): Response
    {
        return $this->render('backoffice/pages/categories/edit_form.html.twig');
    }

    #[Route(
        path: '/admin/categories/create',
        name: 'app_backoffice_categories_store',
        methods: ['POST']
    )]
    public function store(Request $request): Response
    {
        /** @var StoreRequestDto $dto */
        $dto = $this->validate($request, StoreRequestDto::class);

        $this->categoryService->createCategory(name: $dto->name);

        return $this->redirectToRoute('app_backoffice_categories_index');
    }

//    public function edit(): Response
//    {
//
//    }

//    public function update(): Response
//    {
//
//    }
}
