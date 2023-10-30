<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('workspace_id')->constrained();
            $table->decimal('st_total_price', 15, 2);
            $table->string('st_session_id');
            $table->string('st_cus_id', 1024)->nullable();
            $table->string('st_sub_id', 1024)->nullable();
            $table->string('st_payment_method', 1024)->nullable();
            $table->string('st_payment_status', 1024)->nullable();
            $table->dateTimeTz('st_end_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable()->index();
            $table->unsignedInteger('unsubscribe_event_id')->nullable();
            $table->softDeletes();

            $table->timestamps();

            $table->foreign('unsubscribe_event_id')->references('id')
                ->on('unsubscribe_event_types');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribers');
    }
};
