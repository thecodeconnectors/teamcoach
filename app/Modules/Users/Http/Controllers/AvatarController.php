<?php

namespace App\Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AvatarController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => collect(config('avatars.avatars'))->map(fn ($item) => [
                'id' => url("storage/avatars/{$item}"),
                'name' => $item,
            ])->toArray(),
        ]);
    }
}
