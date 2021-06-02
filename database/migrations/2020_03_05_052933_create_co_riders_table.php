<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoRidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_riders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('co_ride_id');
            $table->integer('co_rider_id');
            $table->integer('ride_id');
            $table->integer('posted_by_driver')->comment('1 Yes 0 No')->default(0);
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
        Schema::dropIfExists('co_riders');
    }
}
