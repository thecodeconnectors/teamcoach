<?php

namespace App\Http\Controllers;

use App\Enums\EventType;
use App\Http\Resources\EventTypeResource;

class PlayerActionEventTypeController extends Controller
{
    public function index(): EventTypeResource
    {
        return new EventTypeResource(EventType::playerActionEventTypes());
    }
}
