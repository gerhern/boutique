<?php

namespace App\enums;

enum PaymentStatus : string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
}
