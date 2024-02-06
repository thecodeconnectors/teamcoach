<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => config('settings'),
        ]);
    }
}
