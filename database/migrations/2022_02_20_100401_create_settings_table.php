<?php

use App\Models\Account;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->foreignIdFor(Account::class);
            $table->string('key');
            $table->text('value');
            $table->string('type')->default('text');
            $table->string('options')->nullable();
            $table->timestamps();
            $table->unique(['key', 'account_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
