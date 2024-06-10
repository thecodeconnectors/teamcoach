<?php

namespace App\Providers;

use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use App\Models\Training;
use App\Modules\Users\Models\User;
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
            'team' => Team::class,
            'user' => User::class,
            'game' => Game::class,
            'player' => Player::class,
            'training' => Training::class,
        ]);
    }
}
