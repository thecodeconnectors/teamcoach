<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainingAttendanceRequest;
use App\Http\Resources\TrainingResource;
use App\Models\Player;
use App\Models\Training;

class TrainingPlayerController extends Controller
{
    public function store(StoreTrainingAttendanceRequest $request, Training $training): TrainingResource
    {
        $players = Player::query()->findMany($request->validated('ids'));

        $training->createAttendanceList($players);

        return new TrainingResource($training);
    }

}
