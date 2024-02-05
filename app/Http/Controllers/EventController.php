<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Event::class);
    }

    public function store(StoreEventRequest $request): EventResource
    {
        $event = Event::query()->create($request->validated());

        return new EventResource($event);
    }

    public function show(Event $event): EventResource
    {
        return new EventResource($event);
    }

    public function update(UpdateEventRequest $request, Event $event): EventResource
    {
        $event->update($request->validated());

        return new EventResource($event);
    }

    public function destroy(Event $event): Response
    {
        $event->delete();

        return response()->noContent();
    }
}
