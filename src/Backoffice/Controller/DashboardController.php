<?php

namespace App\Backoffice\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    path: '/admin',
    name: 'app_backoffice_dashboard',
    methods: ['GET']
)]
class DashboardController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('backoffice/pages/dashboard.html.twig');
    }
}
