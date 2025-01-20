<?php

namespace App\Enum;

enum WardrobeStatus: string
{
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
    case DONATED = 'donated';
}
