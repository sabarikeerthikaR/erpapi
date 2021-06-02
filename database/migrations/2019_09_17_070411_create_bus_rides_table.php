<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_rides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bus_id');
            $table->string('source');
            $table->string('source_latitude');
            $table->string('source_longitude');
            $table->string('destination_latitude');
            $table->string('destination_longitude');
            $table->string('destination');
            $table->integer('remaining_seats');
            $table->integer('per_seat_price');
            $table->integer('driver_id');
            $table->string('start_time');
            $table->string('finish_time');
            $table->tinyInteger('status')->comment('1 Scheduled 2 Started  3 Finished')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bus_rides');
    }
}
