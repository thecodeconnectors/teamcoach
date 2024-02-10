<?php

use App\Models\Account;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class);
            $table->foreignIdFor(Team::class, 'team_id');
            $table->foreignIdFor(Team::class, 'opponent_id');
            $table->unsignedInteger('team_points')->default(0);
            $table->unsignedInteger('opponent_points')->default(0);
            $table->boolean('is_public')->default(0);
            $table->boolean('is_away_game')->default(0);
            $table->string('url_secret', 255)->nullable();
            $table->unsignedInteger('parts')->default(2);
            $table->unsignedInteger('part_duration')->default(45);
            $table->unsignedInteger('break_duration')->default(15);
            $table->timestamp('start_at');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
