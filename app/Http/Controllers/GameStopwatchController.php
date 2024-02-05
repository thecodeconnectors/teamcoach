<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateRequest;
use App\Http\Resources\GamePlayResource;
use App\Models\Game;

class GameStopwatchController extends Controller
{
    public function start(DateRequest $request, Game $game): GamePlayResource
    {
        if (!$game->isStarted()) {
            $game->start($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }

    public function finish(DateRequest $request, Game $game): GamePlayResource
    {
        if ($game->isPlaying()) {
            $game->finish($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }

    public function pause(DateRequest $request, Game $game): GamePlayResource
    {
        if ($game->isPlaying() && !$game->pausedEvent()) {
            $game->pause($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }

    public function resume(DateRequest $request, Game $game): GamePlayResource
    {
        if ($game->pausedEvent()) {
            $game->resume($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }
}
