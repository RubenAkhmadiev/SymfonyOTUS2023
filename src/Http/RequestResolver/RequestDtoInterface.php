<?php

namespace App\Http\RequestResolver;

use Symfony\Component\HttpFoundation\Request;

interface RequestDtoInterface
{
    public static function fromRequest(Request $request): RequestDtoInterface;
}
