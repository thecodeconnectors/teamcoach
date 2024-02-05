<?php

namespace App\Http\Controllers;

use App\Enums\GamePlayerType;
use App\Http\Resources\GamePlayerTypeResource;

class GamePlayerTypeController extends Controller
{
    public function index(): GamePlayerTypeResource
    {
        return new GamePlayerTypeResource(GamePlayerType::toArray());
    }
}
