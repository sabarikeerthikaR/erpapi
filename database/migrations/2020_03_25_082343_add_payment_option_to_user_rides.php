<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentOptionToUserRides extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_rides', function (Blueprint $table) {
            $table->tinyInteger('payment_option')->comment('1 Credit card, 2 Debit card, 3 wallet 4 Net Banking 5 Cash');
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
            $table->dropColumn('payment_option');
        });
    }
}
