<?php

namespace App\Http\Controllers;

use App\Enums\Position;
use App\Http\Resources\PositionResource;

class PositionController extends Controller
{
    public function index(): PositionResource
    {
        return new PositionResource(Position::toArray());
    }
}
