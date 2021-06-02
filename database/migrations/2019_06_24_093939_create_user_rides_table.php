<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('source');
            $table->string('destination');
            $table->string('source_latitude')->nullable();
            $table->string('source_longitude')->nullable();
            $table->string('destination_latitude')->nullable();
            $table->string('destination_longitude')->nullable();
            $table->tinyInteger('ride_type')->comment('1 Single 2 Pool');
            $table->integer('passengers_preference')->nullable();
            $table->tinyInteger('ride_option')->comment('1 Now 2 Later');
            $table->string('ride_date')->nullable();
            $table->string('ride_time')->nullable();
            $table->string('requested_at')->nullable();
            $table->string('start_time')->nullable();
            $table->string('complete_time')->nullable();
            $table->integer('duration')->nullable();
            $table->decimal('distance', 11, 2)->nullable();
            $table->integer('driver_id')->nullable();
            $table->integer('vehicle_type');
            $table->decimal('base_fare', 11, 2);
            $table->decimal('per_km', 11, 2);
            $table->decimal('per_minute_wait_charge', 11, 2);
            $table->string('vehicle_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('cancel_reason', 1000)->nullable();
            $table->tinyInteger('status')->comment('1 Posted 2 Accepted 3 Started 4 Completed 5 Canceled')->default(1);


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
        Schema::dropIfExists('user_rides');
    }
}
