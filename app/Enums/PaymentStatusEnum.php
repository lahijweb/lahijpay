<?php

namespace App\Enums;

enum PaymentStatusEnum:string
{
    case PENDING = 'pending';
    case  ACCEPTED = 'accepted';
    case  REJECTED = 'rejected';
}
