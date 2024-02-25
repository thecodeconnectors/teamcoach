<?php

namespace App\Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Attendance\Enums\AttendanceState;
use App\Modules\Attendance\Http\Resources\AttendanceStateResource;

class AttendanceStateController extends Controller
{
    public function index(): AttendanceStateResource
    {
        return new AttendanceStateResource(AttendanceState::toArray());
    }
}
