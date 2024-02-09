<?php

namespace App\Http\Controllers;

use App\Http\Requests\GamePublishRequest;
use App\Http\Resources\GamePlayResource;
use App\Models\Game;
use Symfony\Component\HttpFoundation\Response;

class GamePublishController extends Controller
{
    public function show(string $urlSecret): GamePlayResource|Response
    {
        /** @var Game $game */
        $game = Game::query()->where('url_secret', $urlSecret)->first();

        if (!$game->is_public) {
            return response('', Response::HTTP_FORBIDDEN);
        }

        return new GamePlayResource($game);
    }

    public function publish(GamePublishRequest $request, Game $game): GamePlayResource
    {
        $game->makePublic();

        return new GamePlayResource($game);
    }

    public function unpublish(GamePublishRequest $request, Game $game): GamePlayResource
    {
        $game->makePrivate();

        return new GamePlayResource($game);
    }
}
