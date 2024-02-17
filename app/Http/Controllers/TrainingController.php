<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrainingRequest;
use App\Http\Requests\UpdateTrainingRequest;
use App\Http\Resources\TrainingResource;
use App\Models\Training;
use App\Repositories\TrainingRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TrainingController extends Controller
{
    public function __construct(protected TrainingRepository $repository)
    {
        $this->authorizeResource(Training::class);
    }

    public function index(Request $request): ResourceCollection
    {
        $trainings = $this->repository->setRequest($request)->paginate();

        $trainings->load('team');

        return TrainingResource::collection($trainings)->preserveQuery();
    }

    public function store(StoreTrainingRequest $request): TrainingResource
    {
        $training = $this->repository->setRequest($request)->store($request->validated());

        return new TrainingResource($training);
    }

    public function show(Training $training): TrainingResource
    {
        $training->load('team', 'team.players');
        
        return new TrainingResource($training);
    }

    public function update(UpdateTrainingRequest $request, Training $training): TrainingResource
    {
        $training->update($request->validated());

        return new TrainingResource($training);
    }

    public function destroy(Training $training): Response
    {
        $training->delete();

        return response()->noContent();
    }
}
