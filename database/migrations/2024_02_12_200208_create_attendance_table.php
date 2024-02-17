<?php

use App\Models\Account;
use App\Modules\Attendance\Enums\AttendanceState;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class);
            $table->morphs('attendable'); // For Game or Training
            $table->morphs('attendee'); // For User or Player
            $table->string('state')->default(AttendanceState::Pending->value);
            $table->timestamp('state_changed_at')->nullable();
            $table->timestamps();

            $table->unique([
                'attendable_type',
                'attendable_id',
                'attendee_type',
                'attendee_id',
            ], 'attendances_attendable_attendee_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
