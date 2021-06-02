<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDriversProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('drivers_profile', function (Blueprint $table) {
            $table->tinyInteger('authenticated_license')->comment('1 Yes 0 No');
            $table->tinyInteger('experienced')->comment('1 Yes 0 No');
            $table->tinyInteger('experience_years')->comment('1 (0-1)year 2 (2-5)years 3 (5+)years')->nullable();
            $table->tinyInteger('functional_ac')->comment('1 Yes 0 No');
            $table->string('reason_for_no_ac')->nullable();
            $table->tinyInteger('vehicle_condition')->comment('1 Neat 2 Average 3 Old');
            $table->tinyInteger('remittances')->comment('1 Within 48 hours  2  A Week 3 A Month')->nullable();
            $table->string('reason_for_applying')->nullable();
            $table->string('comment')->nullable();
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
