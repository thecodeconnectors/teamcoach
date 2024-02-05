<?php

namespace App\Enums;

enum GamePlayerType: string
{
    case Playing = 'playing';

    case Substitute = 'substitute';

    case Absent = 'absent';
    
    public static function toArray(): array
    {
        return [
            self::Playing->value => self::Playing->name,
            self::Substitute->value => self::Substitute->name,
            self::Absent->value => self::Absent->name,
        ];
    }
}
