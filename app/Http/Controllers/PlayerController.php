<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Repositories\PlayerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class PlayerController extends Controller
{
    public function __construct(protected PlayerRepository $repository)
    {
        $this->authorizeResource(Player::class);
    }

    public function index(Request $request): ResourceCollection
    {
        $players = $this->repository->setRequest($request)->paginate();

        return PlayerResource::collection($players)->preserveQuery();
    }

    public function store(StorePlayerRequest $request): PlayerResource
    {
        $player = $this->repository->setRequest($request)->store($request->validated());

        return new PlayerResource($player);
    }

    public function show(Player $player): PlayerResource
    {
        return new PlayerResource($player);
    }

    public function update(UpdatePlayerRequest $request, Player $player): PlayerResource
    {
        $player->update($request->validated());

        return new PlayerResource($player);
    }

    public function destroy(Player $player): Response
    {
        $player->delete();

        return response()->noContent();
    }
}
