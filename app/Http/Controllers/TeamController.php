<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function __construct(protected TeamRepository $repository)
    {
        $this->authorizeResource(Team::class);
    }

    public function index(Request $request): ResourceCollection
    {
        $teams = $this->repository->setRequest($request)->paginate();

        return TeamResource::collection($teams)->preserveQuery();
    }

    public function store(StoreTeamRequest $request): TeamResource
    {
        $team = $this->repository->setRequest($request)->store($request->validated());

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
