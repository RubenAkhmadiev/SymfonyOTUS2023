<?php

namespace App\Backoffice\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route(
        path: '/admin/login',
        name: 'app_backoffice_login',
        methods: ['GET']
    )]
    public function login(): Response
    {
        return $this->render('backoffice/pages/login.html.twig');
    }
}
