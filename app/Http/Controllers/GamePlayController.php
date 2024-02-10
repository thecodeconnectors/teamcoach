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
        /** @var Player $playerA */
        $playerA = $game->players()->findOrFail($request->validated('player_id_a'));

        /** @var Player $playerB */
        $playerB = $game->players()->findOrFail($request->validated('player_id_b'));

        $game->substitutePlayer($playerA, $playerB, now());

        return new GamePlayResource($game);
    }
}
