<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {

            $table->id();
            $table->string('name')->unique();
            $table->string('avatar')->nullable();
            $table->decimal('price', 7);
            $table->string('interval');
            $table->boolean('is_subscription')->default(true);
            $table->integer('trial_period_days');
            $table->string('lookup_key', 255);
            $table->string('st_plan_id', 255);
            $table->string('number_app_building', 255)->default(0);
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packs');
    }
};
