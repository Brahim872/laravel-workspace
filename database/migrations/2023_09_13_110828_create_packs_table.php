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
        Schema::create('packs', function (Blueprint $table) {
            $table->integer('id', true, true)->nullable(false)->index();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->bigInteger('coust')->nullable();
            $table->bigInteger('descount')->nullable();
            $table->text('discription')->nullable();

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
