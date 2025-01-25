<?php

namespace App\Enum;

enum WardrobeSeason: string
{
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case AUTUMN = 'autumn';
    case WINTER = 'winter';
    case ALL = 'all';

    /**
     * @return array<WardrobeSeason>
     */
    public static function getSeasons(): array
    {
        return [
            self::SPRING,
            self::SUMMER,
            self::AUTUMN,
            self::WINTER,
            self::ALL,
        ];
    }
}
