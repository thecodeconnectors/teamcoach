<?php

namespace App\Enums;

enum Position: string
{
    case Goal = 'goal';

    case Defense = 'defense';

    case Mid = 'mid';

    case Attack = 'attack';

    public static function toArray(): array
    {
        return [
            self::Goal->value => self::Goal->name,
            self::Defense->value => self::Defense->name,
            self::Mid->value => self::Mid->name,
            self::Attack->value => self::Attack->name,
        ];
    }
}
