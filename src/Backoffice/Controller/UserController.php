<?php

namespace App\Backoffice\Controller;

use App\Backoffice\Entity\User;
use App\Backoffice\RequestDto\User\IndexRequestDto;
use App\Backoffice\Service\UserService;
use App\Backoffice\View\Table;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    use ValidateTrait;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface     $validator,
        protected UserService  $userService,
    ) {
        $this->setValidator($validator);
    }

    #[Route(
        path: '/admin/users',
        name: 'app_backoffice_users_index',
        methods: ['GET']
    )]
    public function index(Request $request): Response
    {
        /** @var IndexRequestDto $dto */
        $dto = $this->validate($request, IndexRequestDto::class);

        $result = $this->userService->getAll(
            limit: $dto->limit,
            page: $dto->page,
        );

        $body = array_map(static fn(User $user) => [
            'id' => $user->getId(),
        ], $result['items']);

        return $this->render('backoffice/pages/users/index.html.twig', [
            'users' => (new Table())
                ->setHeader('ID')
                ->setData($body)
                ->setPage($dto->page, $result['has_more'], $dto->limit)
        ]);
    }
}
