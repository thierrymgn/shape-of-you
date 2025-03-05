<?php

namespace App\Enum;

enum WardrobeStatus: string
{
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
    case DONATED = 'donated';

    /**
     * @return array<WardrobeStatus>
     */
    public static function getStatuses(): array
    {
        return [
            self::ACTIVE,
            self::ARCHIVED,
            self::DONATED,
        ];
    }
}
