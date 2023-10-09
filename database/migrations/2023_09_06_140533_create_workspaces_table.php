<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->string('name');
            $table->foreignId('plan_id')->unsigned()->nullable();
            $table->foreignId('payment_id')->nullable();
            $table->string('avatar')->nullable();
            $table->dateTime('deactivated_at')->nullable();
            $table->integer('count_app_building')->unsigned()->default(0);

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('plan_id')->references('id')->on('plans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};
