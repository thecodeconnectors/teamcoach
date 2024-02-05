<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (!$this->app->isLocal()) {
            URL::forceScheme('https');
        }

        Relation::enforceMorphMap([
            'user' => User::class,
            'team' => Team::class,
            'player' => Player::class,
            'game' => Game::class,
        ]);
    }
}
