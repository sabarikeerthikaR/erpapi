<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRideTypeToRateReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rate_reviews', function (Blueprint $table) {
            $table->tinyInteger('ride_type')->comment('1 User posted ride 2 Driver posted ride');
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
            $table->dropColumn('ride_type');
        });
    }
}
