<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CurrentUserController extends Controller
{
    public function show(Request $request): null|UserResource|Response
    {
        if ($user = $request->user()) {
            return new UserResource($user);
        }

        return response()->noContent();
    }
}
