<?php

use App\Models\Player;
use App\Models\Team;
use App\Modules\Partners\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('team_player', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class);
            $table->foreignIdFor(Player::class);

            $table->timestamps();

            $table->unique(['team_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_player');
    }
};
