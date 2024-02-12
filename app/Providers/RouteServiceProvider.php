<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware(['web'])->group(base_path('routes/web.php'));
            Route::middleware(['json', 'web'])->prefix('api')->group(base_path('routes/auth.php'));
            Route::middleware(['json', 'web'])->prefix('api')->group(base_path('routes/api-public.php'));
            Route::middleware(['json', 'web', 'auth', 'verified'])->prefix('api')->group(base_path('routes/api.php'));
        });
    }
}
