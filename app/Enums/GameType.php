<?php

namespace App\Enums;

enum GameType: string
{
    case Competition = 'competition';
    case FriendlyCompetition = 'friendly-competition';
    case Cup = 'cup';
    case Tournament = 'tournament';

    public static function toArray(): array
    {
        return [
            self::Competition->value => self::Competition->name,
            self::FriendlyCompetition->value => self::FriendlyCompetition->name,
            self::Cup->value => self::Cup->name,
            self::Tournament->value => self::Tournament->name,
        ];
    }
}
