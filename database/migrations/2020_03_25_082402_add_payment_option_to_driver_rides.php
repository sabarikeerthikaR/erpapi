<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentOptionToDriverRides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('driver_rides', function (Blueprint $table) {
            $table->tinyInteger('payment_option')->comment('1 Credit card, 2 Debit card, 3 wallet 4 Net Banking 5 Cash')->default(5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('driver_rides', function (Blueprint $table) {
            $table->dropColumn('payment_option');
        });
    }
}
