<?php

namespace App\Enum;

enum PartnerOrderStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}
