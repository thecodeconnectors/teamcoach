<?php

namespace App\Modules\Attendance\Enums;

enum AttendanceState: string
{
    case Pending = 'pending';

    case Accepted = 'accepted';

    case Declined = 'declined';

    case Maybe = 'maybe';

    public static function toArray(): array
    {
        return [
            self::Pending->value => self::Pending->name,
            self::Accepted->value => self::Accepted->name,
            self::Maybe->value => self::Maybe->name,
            self::Declined->value => self::Declined->name,
        ];
    }
}
