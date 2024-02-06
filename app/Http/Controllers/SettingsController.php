<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        config()->set('settings.logo', url((config('settings.logo'))));

        return response()->json([
            'data' => config('settings'),
        ]);
    }
}
