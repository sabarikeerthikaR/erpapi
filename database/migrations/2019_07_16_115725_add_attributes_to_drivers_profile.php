<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesToDriversProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers_profile', function (Blueprint $table) {
            $table->string('vehicle_model');
            $table->string('vehicle_brand');
            $table->string('chassis_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers_profile', function (Blueprint $table) {
           //
        });
    }
}
