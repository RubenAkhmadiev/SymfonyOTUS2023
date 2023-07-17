<?php

namespace App\Enum;

enum OrderStatusEnum :string
{
    case NEW = 'new';
    case PROCESS = 'process';
    case CANCELED = 'canceled';
    case DONE = 'done';
}
