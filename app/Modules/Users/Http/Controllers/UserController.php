<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Requests\UpdateUserRequest;
use App\Modules\Users\Http\Resources\UserResource;
use App\Modules\Users\Models\User;
use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class UserController extends Controller
{
    public function __construct(protected UserRepository $repository)
    {
        $this->authorizeResource(User::class);
    }

    public function index(Request $request): ResourceCollection
    {
        $users = $this->repository->setRequest($request)->paginate();

        return UserResource::collection($users)->preserveQuery();
    }

    public function store(Request $request): UserResource
    {
        $user = app(CreatesNewUsers::class)->create($request->all());

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }
}
