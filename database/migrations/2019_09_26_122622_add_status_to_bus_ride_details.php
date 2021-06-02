<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToBusRideDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bus_ride_details', function (Blueprint $table) {
            $table->tinyInteger('status')->comment('1 Booked 2 Canceled')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bus_ride_details', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
