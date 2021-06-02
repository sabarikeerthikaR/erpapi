<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesToRateReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rate_reviews', function (Blueprint $table) {
            $table->tinyInteger('is_driver_courteous')->comment('1 Yes 0 No')->nullable();
            $table->tinyInteger('is_vehicle_clean')->comment('1 Yes 0 No')->nullable();
            $table->tinyInteger('is_driver_careful')->comment('1 Yes 0 No')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rate_reviews', function (Blueprint $table) {
            //
        });
    }
}
