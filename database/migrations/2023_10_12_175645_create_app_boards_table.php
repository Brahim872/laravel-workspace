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
        Schema::create('app_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->unsigned()->constrained();
            $table->string("name");
            $table->boolean("is_public");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_boards');
    }
};
