<?php

namespace App\Http\Controllers;

use App\Enums\GamePlayerType;
use App\Enums\Position;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class GameController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Game::class);
    }

    public function index(): ResourceCollection
    {
        $games = Game::query()->paginate((int)request('per_page', 10));

        return GameResource::collection($games)->preserveQuery();
    }

    public function store(StoreGameRequest $request): GameResource
    {
        $gameAttributes = Arr::except($request->validated(), ['player_ids', 'opponent_name']);
        $opponent = Team::query()->create(['name' => $request->validated('opponent_name')]);

        $gameAttributes['opponent_id'] = $opponent->id;

        /** @var Game $game */
        $game = Game::query()->create($gameAttributes);

        foreach (Player::query()->where('team_id', $game->team_id)->get() as $player) {
            $game->addPlayer($player);
        }

        return new GameResource($game);
    }

    public function show(Game $game): GameResource
    {
        return new GameResource($game);
    }

    public function update(UpdateGameRequest $request, Game $game): GameResource
    {
        $gameAttributes = Arr::except($request->validated(), ['players', 'opponent_name']);

        $game->update($gameAttributes);

        if ($opponentName = $request->validated('opponent_name')) {
            $game->opponent()->update(['name' => $opponentName]);
        }

        foreach ($request->validated('players') as $player) {
            $game->updatePlayer($player['id'], Position::tryFrom($player['position']), GamePlayerType::tryFrom($player['type']));
        }

        return new GameResource($game);
    }

    public function destroy(Game $game): Response
    {
        $game->delete();

        return response()->noContent();
    }
}
