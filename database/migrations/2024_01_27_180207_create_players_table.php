<?php

use App\Models\Team;
use App\Modules\Partners\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class);
            $table->string('name');
            $table->string('position')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
