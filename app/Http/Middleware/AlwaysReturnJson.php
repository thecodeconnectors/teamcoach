<?php

namespace App\Http\Middleware;

use Closure;

class AlwaysReturnJson
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->header('Content-Type', 'application/json');
        }

        return $response;
    }
}
