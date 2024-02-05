<?php

use App\Models\Team;
use App\Modules\Partners\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'team_id');
            $table->foreignIdFor(Team::class, 'opponent_id');
            $table->unsignedInteger('team_points')->default(0);
            $table->unsignedInteger('opponent_points')->default(0);
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
