<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOngoingRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ongoing_rides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ride_id');
            $table->integer('user_id');
            $table->integer('driver_id');
            $table->integer('vehicle_type_id');
            $table->tinyInteger('type')->comment('1 Single 2 Pool');
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
        Schema::dropIfExists('ongoing_rides');
    }
}
