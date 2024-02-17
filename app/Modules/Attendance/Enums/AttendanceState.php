<?php

namespace App\Modules\Attendance\Enums;

enum AttendanceState: string
{
    case Pending = 'pending';

    case Accepted = 'accepted';

    case Declined = 'declined';
}
