<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Team::class);
    }

    public function index(): ResourceCollection
    {
        $teams = Team::query()->paginate((int)request('per_page', 10));

        return TeamResource::collection($teams)->preserveQuery();
    }

    public function store(StoreTeamRequest $request): TeamResource
    {
        $team = Team::query()->create($request->validated());

        return new TeamResource($team);
    }

    public function show(Team $team): TeamResource
    {
        return new TeamResource($team);
    }

    public function update(UpdateTeamRequest $request, Team $team): TeamResource
    {
        $team->update($request->validated());

        return new TeamResource($team);
    }

    public function destroy(Team $team): Response
    {
        $team->delete();

        return response()->noContent();
    }
}
