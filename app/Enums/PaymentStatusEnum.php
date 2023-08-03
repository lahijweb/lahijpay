<?php

namespace App\Enums;

enum PaymentStatusEnum:string
{
    case PENDING = 'PENDING';
    case  ACCEPTED = 'ACCEPTED';
    case  REJECTED = 'REJECTED';
}
