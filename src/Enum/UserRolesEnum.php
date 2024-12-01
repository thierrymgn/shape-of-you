<?php

declare(strict_types=1);

namespace App\Enum;

enum UserRolesEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case MODERATOR = 'moderator';
}
