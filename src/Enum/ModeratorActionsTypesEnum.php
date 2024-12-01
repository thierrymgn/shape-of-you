<?php

declare(strict_types=1);

namespace App\Enum;

enum ModeratorActionsTypesEnum: string
{
    case BAN = 'ban';
    case UNBAN = 'unban';
    case SUSPEND = 'suspend';
    case UNSUSPEND = 'unsuspend';
    case DELETE = 'delete';
    case RESTORE = 'restore';
    case MANAGE_NOTIFICATIONS = 'manage_notifications';
    case ADD_LINK = 'add_link';
    case REMOVE_LINK = 'remove_link';
    case MODERATE_CONTENT = 'moderate_content';
}
