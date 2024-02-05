<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CsrfCookieController
{
    public function show(Request $request): JsonResponse
    {
        return (new JsonResponse(['cookie' => $request->getUserInfo()], 200))
            ->header('Content-Type', 'application/json');
    }
}
