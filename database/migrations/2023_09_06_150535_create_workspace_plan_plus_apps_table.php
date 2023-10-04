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
        Schema::create('workspace_plan_plus_apps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('plan_plus_apps_id')->unsigned();
            $table->bigInteger('workspace_id')->unsigned();
            $table->integer('type_user')->default(0);

            $table->foreign('plan_plus_apps_id')->references('id')->on('plan_plus_apps')
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
        Schema::dropIfExists('workspace_plan_plus_apps');
    }
};
