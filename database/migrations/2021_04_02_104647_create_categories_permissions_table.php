<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('display_name', 80);
            $table->tinyInteger('type')->unsigned();
            $table->tinyInteger('position')->unsigned()->default(0);
            $table->boolean('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories_permissions');
    }
}
