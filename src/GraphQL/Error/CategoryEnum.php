<?php

namespace App\GraphQL\Error;

use MyCLabs\Enum\Enum;

/**
 * @method static self ACCESS_DENIED()
 * @method static self INVALID_ARGUMENT()
 * @method static self NOT_FOUND()
 * @method static self NOT_SUPPORTED()
 *
 * @psalm-immutable 
 */
class CategoryEnum extends Enum
{
    public const ACCESS_DENIED = 'access_denied';
    public const INVALID_ARGUMENT = 'invalid_argument';
    public const NOT_FOUND = 'not_found';
    public const NOT_SUPPORTED = 'not_supported';
}