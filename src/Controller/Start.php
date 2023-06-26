<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/', methods: ['GET'])]
class Start
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse('App');
    }
}
