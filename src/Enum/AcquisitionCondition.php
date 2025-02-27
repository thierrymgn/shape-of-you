<?php

namespace App\Enum;

enum AcquisitionCondition: string
{
    case NEW = 'New';
    case USED = 'Used';
    case HANDMADE = 'Handmade';
}
