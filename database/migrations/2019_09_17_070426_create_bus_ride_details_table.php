<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusRideDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_ride_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bus_ride_id');
            $table->integer('user_id');
            $table->integer('no_of_seats');
            $table->integer('per_seat_price');
            $table->integer('total_price');
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
        Schema::dropIfExists('bus_ride_details');
    }
}
