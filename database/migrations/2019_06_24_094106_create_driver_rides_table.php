<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_rides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('driver_id');
            $table->string('source');
            $table->string('destination');
            $table->string('source_latitude')->nullable();
            $table->string('source_longitude')->nullable();
            $table->string('destination_latitude')->nullable();
            $table->string('destination_longitude')->nullable();
            $table->tinyInteger('ride_type')->comment('1 Single 2 Pool');
            $table->integer('passengers_preference');
            $table->string('ride_date');
            $table->string('ride_time');
            $table->string('start_time')->nullable();
            $table->string('complete_time')->nullable();
            $table->integer('duration')->nullable();
            $table->decimal('distance',11,2)->nullable();
            $table->decimal('base_fare',11,2);
            $table->string('cancel_reason', 1000)->nullable();
            $table->tinyInteger('status')->comment('1 Posted  2 Started 3 Completed 4 Canceled')->default(1);
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
        Schema::dropIfExists('driver_rides');
    }
}
