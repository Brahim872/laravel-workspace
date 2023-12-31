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
//            $table->id();
            $table->foreignId("board_id")->unsigned()->constrained('boards');
            $table->foreignId("app_id")->unsigned()->constrained();
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
