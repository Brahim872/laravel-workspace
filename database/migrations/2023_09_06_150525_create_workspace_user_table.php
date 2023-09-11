<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the workspace_user.
     */
    public function up(): void
    {
        Schema::create('workspace_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('workspace_id')->unsigned();
            $table->integer('type_user')->default(0);

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('workspace_id')->references('id')->on('workspaces')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the workspace_user.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_user');
    }
};
