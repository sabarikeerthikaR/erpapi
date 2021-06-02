<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostedByDriverToUserRides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_rides', function (Blueprint $table) {
            $table->tinyInteger('posted_by_driver')->comment('1 Yes 0 No');
            $table->integer('driver_ride_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_rides', function (Blueprint $table) {
            $table->dropColumn('posted_by_driver');
            $table->dropColumn('driver_ride_id');
        });
    }
}
