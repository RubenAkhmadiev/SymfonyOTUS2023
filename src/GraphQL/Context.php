<?php

namespace App\GraphQL;

use Symfony\Component\HttpFoundation\Request;

class Context
{
    public function __construct(
        public Request $httpRequest
    ) {
    }
}
