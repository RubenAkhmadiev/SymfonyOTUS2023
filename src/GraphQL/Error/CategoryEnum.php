<?php

namespace App\GraphQL\Error;

enum CategoryEnum :string
{
    case ACCESS_DENIED = 'access_denied';
    case INVALID_ARGUMENT = 'invalid_argument';
    case NOT_FOUND = 'not_found';
    case NOT_SUPPORTED = 'not_supported';
}
