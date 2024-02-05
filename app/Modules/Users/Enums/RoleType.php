<?php

namespace App\Modules\Users\Enums;

enum RoleType: string
{
    case User = 'user';

    case Admin = 'admin';
}
