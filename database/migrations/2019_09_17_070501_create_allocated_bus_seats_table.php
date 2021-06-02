<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllocatedBusSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocated_bus_seats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('bus_ride_detail_id');
            $table->string('user_name')->nullable();
            $table->integer('seat_no');
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
        Schema::dropIfExists('allocated_bus_seats');
    }
}
