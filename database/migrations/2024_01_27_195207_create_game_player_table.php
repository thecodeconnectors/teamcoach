<?php

use App\Enums\GamePlayerType;
use App\Models\Game;
use App\Models\Player;
use App\Modules\Partners\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('game_player', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Game::class);
            $table->foreignIdFor(Player::class);
            $table->string('type')->default(GamePlayerType::Playing->value);
            $table->string('position')->nullable();
            $table->timestamps();

            $table->unique(['game_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_player');
    }
};
