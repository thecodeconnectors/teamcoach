<?php

namespace App\Http\Controllers;

use App\Http\Requests\SwitchGamePlayerRequest;
use App\Http\Resources\GamePlayResource;
use App\Models\Game;
use App\Models\Player;

class GamePlayController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Game::class);
    }

    public function show(Game $game): GamePlayResource
    {
        return new GamePlayResource($game);
    }

    public function switch(SwitchGamePlayerRequest $request, Game $game): GamePlayResource
    {
        /** @var Player $player */
        $player = $game->players()->findOrFail($request->validated('player_id'));

        /** @var Player $substitute */
        $substitute = $game->players()->findOrFail($request->validated('substitute_id'));

        $game->substitutePlayer($player, $substitute, now());

        return new GamePlayResource($game);
    }
}
