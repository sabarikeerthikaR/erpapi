<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_rides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('driver_ride_id');
            $table->integer('co_riders_preference');
            $table->string('pick_up_location');
            $table->string('pick_up_latitude');
            $table->string('pick_up_longitude');
            $table->string('type_of_driver');
            $table->string('preference');
            $table->tinyInteger('status')->comment('1 Requested 2 Accepted 3 Canceled')->default(0);


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
        Schema::dropIfExists('request_rides');
    }
}
