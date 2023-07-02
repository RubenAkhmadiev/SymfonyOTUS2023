<?php

namespace App\Backoffice\Controller;

use App\Backoffice\View\Table;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route(
        path: '/admin/users',
        name: 'app_backoffice_users_index',
        methods: ['GET']
    )]
    public function index(): Response
    {
        $body = [
            ['a','b','c'],
            ['c','v','h'],
        ];

        return $this->render('backoffice/pages/users/index.html.twig', [
            'users' => (new Table())
                ->setHeader('1')
                ->setHeader('2')
                ->setHeader('3')
                ->setData($body)
        ]);
    }
}
