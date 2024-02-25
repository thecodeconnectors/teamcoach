<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainingAttendanceRequest;
use App\Http\Requests\UpdateTrainingAttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Http\Resources\TrainingResource;
use App\Models\Player;
use App\Models\Training;
use App\Modules\Attendance\Enums\AttendanceState;

class TrainingPlayerController extends Controller
{
    public function store(StoreTrainingAttendanceRequest $request, Training $training): TrainingResource
    {
        $players = Player::query()->findMany($request->validated('ids'));

        $training->createAttendanceList($players);

        return new TrainingResource($training);
    }

    public function update(UpdateTrainingAttendanceRequest $request, Training $training, Player $player): AttendanceResource
    {
        $attendance = $training->updateAttendanceState($player, AttendanceState::tryFrom($request->validated('state')));

        return new AttendanceResource($attendance);
    }
}
