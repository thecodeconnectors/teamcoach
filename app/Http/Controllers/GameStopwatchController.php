<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameStopWatchRequest;
use App\Http\Resources\GamePlayResource;
use App\Models\Game;

class GameStopwatchController extends Controller
{
    public function start(GameStopWatchRequest $request, Game $game): GamePlayResource
    {
        if (!$game->isStarted()) {
            $game->start($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }

    public function finish(GameStopWatchRequest $request, Game $game): GamePlayResource
    {
        if ($game->isPlaying()) {
            $game->finish($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }

    public function pause(GameStopWatchRequest $request, Game $game): GamePlayResource
    {
        if ($game->isPlaying() && !$game->pausedEvent()) {
            $game->pause($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }

    public function resume(GameStopWatchRequest $request, Game $game): GamePlayResource
    {
        if ($game->pausedEvent()) {
            $game->resume($request->validated('date_time') ?: now());
        }

        return new GamePlayResource($game);
    }

}
