<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AvatarController extends Controller
{
    public function index(): JsonResponse
    {
        $avatars = [];

        foreach (config('avatars.avatars') as $item) {
            $avatars[] = [
                'id' => url("storage/avatars/{$item}"),
                'name' => $item,
            ];
        }

        return response()->json([
            'data' => $avatars,
        ]);
    }
}
