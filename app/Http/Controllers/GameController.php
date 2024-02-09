<?php

namespace App\Http\Controllers;

use App\Enums\GamePlayerType;
use App\Enums\Position;
use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Repositories\GameRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class GameController extends Controller
{
    public function __construct(protected GameRepository $repository)
    {
        $this->authorizeResource(Game::class);
    }

    public function index(Request $request): ResourceCollection
    {
        $games = $this->repository->setRequest($request)->paginate();

        return GameResource::collection($games)->preserveQuery();
    }

    public function store(StoreGameRequest $request): GameResource
    {
        $this->repository->setRequest($request);

        $opponent = $this->repository->storeOpponent($request->validated('opponent_name'));

        $attributes = Arr::except($request->validated(), ['player_ids', 'opponent_name']);
        $attributes['opponent_id'] = $opponent->id;

        $game = $this->repository->store($attributes)->addTeamPlayers();

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
