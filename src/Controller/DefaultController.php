<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class DefaultController
{
    #[Route(name: '/', methods: ['GET'])]
    public function index(): Response
    {
        return new Response('Default page');
    }
}
