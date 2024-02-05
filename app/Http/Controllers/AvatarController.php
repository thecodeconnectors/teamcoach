<?php

namespace App\Http\Controllers;

class AvatarController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $avatars = [];

        foreach (config('avatars.avatars') as $item) {
            $avatars[] = [
                'id' => $item,
                'name' => $item,
            ];
        }

        return response()->json([
            'data' => $avatars,
        ]);
    }
}
