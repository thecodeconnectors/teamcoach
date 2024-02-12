<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventTypeResource;
use Illuminate\Http\Request;

class PlayerActionEventTypeController extends Controller
{
    public function index(Request $request): EventTypeResource
    {
        return new EventTypeResource($request->user()->account->plan()->playerActionEventTypes());
    }
}
