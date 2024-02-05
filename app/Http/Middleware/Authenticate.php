<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request)
    {
        if ($request->expectsJson() || Str::startsWith($request->path(), 'api')) {
            return response()->json('Not found.', 401);
        }
    }
}
