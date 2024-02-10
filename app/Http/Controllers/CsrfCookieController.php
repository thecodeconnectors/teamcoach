<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CsrfCookieController
{
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'logo' => url((config('app.logo'))),
        ]);
    }
}
