<?php

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestDtoInterface
{
    public static function fromRequest(Request $request): RequestDtoInterface;
}
